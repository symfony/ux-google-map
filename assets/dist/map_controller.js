import { Controller } from '@hotwired/stimulus';
import { Loader } from '@googlemaps/js-api-loader';

class default_1 extends Controller {
    constructor() {
        super(...arguments);
        this.markers = new Map();
        this.infoWindows = [];
        this.polygons = new Map();
        this.polylines = new Map();
    }
    connect() {
        const options = this.optionsValue;
        this.dispatchEvent('pre-connect', { options });
        this.map = this.doCreateMap({ center: this.centerValue, zoom: this.zoomValue, options });
        this.markersValue.forEach((marker) => this.createMarker(marker));
        this.polygonsValue.forEach((polygon) => this.createPolygon(polygon));
        this.polylinesValue.forEach((polyline) => this.createPolyline(polyline));
        if (this.fitBoundsToMarkersValue) {
            this.doFitBoundsToMarkers();
        }
        this.dispatchEvent('connect', {
            map: this.map,
            markers: [...this.markers.values()],
            polygons: [...this.polygons.values()],
            polylines: [...this.polylines.values()],
            infoWindows: this.infoWindows,
        });
    }
    createMarker(definition) {
        this.dispatchEvent('marker:before-create', { definition });
        const marker = this.doCreateMarker(definition);
        this.dispatchEvent('marker:after-create', { marker });
        marker['@id'] = definition['@id'];
        this.markers.set(definition['@id'], marker);
        return marker;
    }
    createPolygon(definition) {
        this.dispatchEvent('polygon:before-create', { definition });
        const polygon = this.doCreatePolygon(definition);
        this.dispatchEvent('polygon:after-create', { polygon });
        polygon['@id'] = definition['@id'];
        this.polygons.set(definition['@id'], polygon);
        return polygon;
    }
    createPolyline(definition) {
        this.dispatchEvent('polyline:before-create', { definition });
        const polyline = this.doCreatePolyline(definition);
        this.dispatchEvent('polyline:after-create', { polyline });
        polyline['@id'] = definition['@id'];
        this.polylines.set(definition['@id'], polyline);
        return polyline;
    }
    createInfoWindow({ definition, element, }) {
        this.dispatchEvent('info-window:before-create', { definition, element });
        const infoWindow = this.doCreateInfoWindow({ definition, element });
        this.dispatchEvent('info-window:after-create', { infoWindow, element });
        this.infoWindows.push(infoWindow);
        return infoWindow;
    }
    markersValueChanged() {
        if (!this.map) {
            return;
        }
        this.markers.forEach((marker) => {
            if (!this.markersValue.find((m) => m['@id'] === marker['@id'])) {
                this.removeMarker(marker);
                this.markers.delete(marker['@id']);
            }
        });
        this.markersValue.forEach((marker) => {
            if (!this.markers.has(marker['@id'])) {
                this.createMarker(marker);
            }
        });
        if (this.fitBoundsToMarkersValue) {
            this.doFitBoundsToMarkers();
        }
    }
    polygonsValueChanged() {
        if (!this.map) {
            return;
        }
        this.polygons.forEach((polygon) => {
            if (!this.polygonsValue.find((p) => p['@id'] === polygon['@id'])) {
                this.removePolygon(polygon);
                this.polygons.delete(polygon['@id']);
            }
        });
        this.polygonsValue.forEach((polygon) => {
            if (!this.polygons.has(polygon['@id'])) {
                this.createPolygon(polygon);
            }
        });
    }
    polylinesValueChanged() {
        if (!this.map) {
            return;
        }
        this.polylines.forEach((polyline) => {
            if (!this.polylinesValue.find((p) => p['@id'] === polyline['@id'])) {
                this.removePolyline(polyline);
                this.polylines.delete(polyline['@id']);
            }
        });
        this.polylinesValue.forEach((polyline) => {
            if (!this.polylines.has(polyline['@id'])) {
                this.createPolyline(polyline);
            }
        });
    }
}
default_1.values = {
    providerOptions: Object,
    center: Object,
    zoom: Number,
    fitBoundsToMarkers: Boolean,
    markers: Array,
    polygons: Array,
    polylines: Array,
    options: Object,
};

let _google;
class map_controller extends default_1 {
    async connect() {
        if (!_google) {
            _google = { maps: {} };
            let { libraries = [], ...loaderOptions } = this.providerOptionsValue;
            const loader = new Loader(loaderOptions);
            libraries = ['core', ...libraries.filter((library) => library !== 'core')];
            const librariesImplementations = await Promise.all(libraries.map((library) => loader.importLibrary(library)));
            librariesImplementations.map((libraryImplementation, index) => {
                const library = libraries[index];
                if (['marker', 'places', 'geometry', 'journeySharing', 'drawing', 'visualization'].includes(library)) {
                    _google.maps[library] = libraryImplementation;
                }
                else {
                    _google.maps = { ..._google.maps, ...libraryImplementation };
                }
            });
        }
        super.connect();
    }
    dispatchEvent(name, payload = {}) {
        this.dispatch(name, {
            prefix: 'ux:map',
            detail: {
                ...payload,
                google: _google,
            },
        });
    }
    doCreateMap({ center, zoom, options, }) {
        options.zoomControl = typeof options.zoomControlOptions !== 'undefined';
        options.mapTypeControl = typeof options.mapTypeControlOptions !== 'undefined';
        options.streetViewControl = typeof options.streetViewControlOptions !== 'undefined';
        options.fullscreenControl = typeof options.fullscreenControlOptions !== 'undefined';
        return new _google.maps.Map(this.element, {
            ...options,
            center,
            zoom,
        });
    }
    doCreateMarker(definition) {
        const { '@id': _id, position, title, infoWindow, extra, rawOptions = {}, ...otherOptions } = definition;
        const marker = new _google.maps.marker.AdvancedMarkerElement({
            position,
            title,
            ...otherOptions,
            ...rawOptions,
            map: this.map,
        });
        if (infoWindow) {
            this.createInfoWindow({ definition: infoWindow, element: marker });
        }
        return marker;
    }
    removeMarker(marker) {
        marker.map = null;
    }
    doCreatePolygon(definition) {
        const { '@id': _id, points, title, infoWindow, rawOptions = {} } = definition;
        const polygon = new _google.maps.Polygon({
            ...rawOptions,
            paths: points,
            map: this.map,
        });
        if (title) {
            polygon.set('title', title);
        }
        if (infoWindow) {
            this.createInfoWindow({ definition: infoWindow, element: polygon });
        }
        return polygon;
    }
    removePolygon(polygon) {
        polygon.setMap(null);
    }
    doCreatePolyline(definition) {
        const { '@id': _id, points, title, infoWindow, rawOptions = {} } = definition;
        const polyline = new _google.maps.Polyline({
            ...rawOptions,
            path: points,
            map: this.map,
        });
        if (title) {
            polyline.set('title', title);
        }
        if (infoWindow) {
            this.createInfoWindow({ definition: infoWindow, element: polyline });
        }
        return polyline;
    }
    removePolyline(polyline) {
        polyline.setMap(null);
    }
    doCreateInfoWindow({ definition, element, }) {
        const { headerContent, content, extra, rawOptions = {}, ...otherOptions } = definition;
        const infoWindow = new _google.maps.InfoWindow({
            headerContent: this.createTextOrElement(headerContent),
            content: this.createTextOrElement(content),
            ...otherOptions,
            ...rawOptions,
        });
        if (element instanceof google.maps.marker.AdvancedMarkerElement) {
            element.addListener('click', () => {
                if (definition.autoClose) {
                    this.closeInfoWindowsExcept(infoWindow);
                }
                infoWindow.open({ map: this.map, anchor: element });
            });
            if (definition.opened) {
                infoWindow.open({ map: this.map, anchor: element });
            }
        }
        else if (element instanceof google.maps.Polygon) {
            element.addListener('click', (event) => {
                if (definition.autoClose) {
                    this.closeInfoWindowsExcept(infoWindow);
                }
                infoWindow.setPosition(event.latLng);
                infoWindow.open(this.map);
            });
            if (definition.opened) {
                const bounds = new google.maps.LatLngBounds();
                element.getPath().forEach((point) => {
                    bounds.extend(point);
                });
                infoWindow.setPosition(bounds.getCenter());
                infoWindow.open({ map: this.map, anchor: element });
            }
        }
        return infoWindow;
    }
    createTextOrElement(content) {
        if (!content) {
            return null;
        }
        if (content.includes('<')) {
            const div = document.createElement('div');
            div.innerHTML = content;
            return div;
        }
        return content;
    }
    closeInfoWindowsExcept(infoWindow) {
        this.infoWindows.forEach((otherInfoWindow) => {
            if (otherInfoWindow !== infoWindow) {
                otherInfoWindow.close();
            }
        });
    }
    doFitBoundsToMarkers() {
        if (this.markers.length === 0) {
            return;
        }
        const bounds = new google.maps.LatLngBounds();
        this.markers.forEach((marker) => {
            if (!marker.position) {
                return;
            }
            bounds.extend(marker.position);
        });
        this.map.fitBounds(bounds);
    }
    centerValueChanged() {
        if (this.map && this.centerValue) {
            this.map.setCenter(this.centerValue);
        }
    }
    zoomValueChanged() {
        if (this.map && this.zoomValue) {
            this.map.setZoom(this.zoomValue);
        }
    }
}

export { map_controller as default };
