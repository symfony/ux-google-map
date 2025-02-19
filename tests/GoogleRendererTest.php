<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Map\Bridge\Google\Tests;

use Symfony\UX\Map\Bridge\Google\GoogleOptions;
use Symfony\UX\Map\Bridge\Google\Renderer\GoogleRenderer;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Polygon;
use Symfony\UX\Map\Polyline;
use Symfony\UX\Map\Test\RendererTestCase;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;

class GoogleRendererTest extends RendererTestCase
{
    public function provideTestRenderMap(): iterable
    {
        $map = (new Map())
            ->center(new Point(48.8566, 2.3522))
            ->zoom(12);
        $marker1 = new Marker(position: new Point(48.8566, 2.3522), title: 'Paris', id: 'marker1');
        $marker2 = new Marker(position: new Point(48.8566, 2.3522), title: 'Lyon', infoWindow: new InfoWindow(content: 'Lyon'), id: 'marker2');
        $marker3 = new Marker(position: new Point(45.8566, 2.3522), title: 'Dijon', id: 'marker3');

        yield 'simple map, with minimum options' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => $map,
        ];

        yield 'with every options' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;id&quot;:&quot;gmap&quot;,&quot;language&quot;:&quot;fr&quot;,&quot;region&quot;:&quot;FR&quot;,&quot;nonce&quot;:&quot;abcd&quot;,&quot;retries&quot;:10,&quot;url&quot;:&quot;https:\/\/maps.googleapis.com\/maps\/api\/js&quot;,&quot;version&quot;:&quot;quarterly&quot;,&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key', id: 'gmap', language: 'fr', region: 'FR', nonce: 'abcd', retries: 10, url: 'https://maps.googleapis.com/maps/api/js', version: 'quarterly'),
            'map' => $map,
        ];

        yield 'with custom attributes' => [
            'expected_render' => '<div data-controller="my-custom-controller symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]" class="map"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => $map,
            'attributes' => ['data-controller' => 'my-custom-controller', 'class' => 'map'],
        ];

        yield 'with markers and infoWindows' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[{&quot;position&quot;:{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},&quot;title&quot;:&quot;Paris&quot;,&quot;infoWindow&quot;:null,&quot;extra&quot;:[],&quot;id&quot;:&quot;marker1&quot;,&quot;@id&quot;:&quot;ff8e1883540bc717&quot;},{&quot;position&quot;:{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},&quot;title&quot;:&quot;Lyon&quot;,&quot;infoWindow&quot;:{&quot;headerContent&quot;:null,&quot;content&quot;:&quot;Lyon&quot;,&quot;position&quot;:null,&quot;opened&quot;:false,&quot;autoClose&quot;:true,&quot;extra&quot;:[]},&quot;extra&quot;:[],&quot;id&quot;:null,&quot;@id&quot;:&quot;adcbe35d50b3c983&quot;}]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->addMarker(new Marker(position: new Point(48.8566, 2.3522), title: 'Paris', id: 'marker1'))
                ->addMarker(new Marker(new Point(48.8566, 2.3522), 'Lyon', infoWindow: new InfoWindow(content: 'Lyon'))),
        ];

        yield 'with all markers removed' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->addMarker($marker1)
                ->addMarker($marker2)
                ->removeMarker($marker1)
                ->removeMarker($marker2),
        ];

        yield 'with marker remove and new ones added' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[{&quot;position&quot;:{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},&quot;title&quot;:&quot;Paris&quot;,&quot;infoWindow&quot;:null,&quot;extra&quot;:[],&quot;id&quot;:&quot;marker1&quot;,&quot;@id&quot;:&quot;ff8e1883540bc717&quot;},{&quot;position&quot;:{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},&quot;title&quot;:&quot;Lyon&quot;,&quot;infoWindow&quot;:{&quot;headerContent&quot;:null,&quot;content&quot;:&quot;Lyon&quot;,&quot;position&quot;:null,&quot;opened&quot;:false,&quot;autoClose&quot;:true,&quot;extra&quot;:[]},&quot;extra&quot;:[],&quot;id&quot;:&quot;marker2&quot;,&quot;@id&quot;:&quot;9514c2b94def6c52&quot;}]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->addMarker($marker3)
                ->removeMarker($marker3)
                ->addMarker($marker1)
                ->addMarker($marker2),
        ];

        yield 'with polygons and infoWindows' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[{&quot;points&quot;:[{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}],&quot;title&quot;:null,&quot;infoWindow&quot;:null,&quot;extra&quot;:[],&quot;id&quot;:null,&quot;@id&quot;:&quot;7cdd432ea54d0ce9&quot;},{&quot;points&quot;:[{&quot;lat&quot;:1.1,&quot;lng&quot;:2.2},{&quot;lat&quot;:3.3,&quot;lng&quot;:4.4},{&quot;lat&quot;:5.5,&quot;lng&quot;:6.6}],&quot;title&quot;:null,&quot;infoWindow&quot;:{&quot;headerContent&quot;:null,&quot;content&quot;:&quot;Polygon&quot;,&quot;position&quot;:null,&quot;opened&quot;:false,&quot;autoClose&quot;:true,&quot;extra&quot;:[]},&quot;extra&quot;:[],&quot;id&quot;:null,&quot;@id&quot;:&quot;9074e0a9ead08c1e&quot;}]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->addPolygon(new Polygon(points: [new Point(48.8566, 2.3522), new Point(48.8566, 2.3522), new Point(48.8566, 2.3522)]))
                ->addPolygon(new Polygon(points: [new Point(1.1, 2.2), new Point(3.3, 4.4), new Point(5.5, 6.6)], infoWindow: new InfoWindow(content: 'Polygon'))),
        ];

        yield 'with polylines and infoWindows' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[{&quot;points&quot;:[{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522},{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}],&quot;title&quot;:null,&quot;infoWindow&quot;:null,&quot;extra&quot;:[],&quot;id&quot;:null,&quot;@id&quot;:&quot;7cdd432ea54d0ce9&quot;},{&quot;points&quot;:[{&quot;lat&quot;:1.1,&quot;lng&quot;:2.2},{&quot;lat&quot;:3.3,&quot;lng&quot;:4.4},{&quot;lat&quot;:5.5,&quot;lng&quot;:6.6}],&quot;title&quot;:null,&quot;infoWindow&quot;:{&quot;headerContent&quot;:null,&quot;content&quot;:&quot;Polygon&quot;,&quot;position&quot;:null,&quot;opened&quot;:false,&quot;autoClose&quot;:true,&quot;extra&quot;:[]},&quot;extra&quot;:[],&quot;id&quot;:null,&quot;@id&quot;:&quot;9074e0a9ead08c1e&quot;}]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->addPolyline(new Polyline(points: [new Point(48.8566, 2.3522), new Point(48.8566, 2.3522), new Point(48.8566, 2.3522)]))
                ->addPolyline(new Polyline(points: [new Point(1.1, 2.2), new Point(3.3, 4.4), new Point(5.5, 6.6)], infoWindow: new InfoWindow(content: 'Polygon'))),
        ];

        yield 'with controls enabled' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->options(new GoogleOptions(
                    zoomControl: true,
                    mapTypeControl: true,
                    streetViewControl: true,
                    fullscreenControl: true,
                )),
        ];

        yield 'without controls enabled' => [
            'expected_render' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:null,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), apiKey: 'api_key'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->options(new GoogleOptions(
                    zoomControl: false,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                )),
        ];

        yield 'with default map id' => [
            'expected_renderer' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;my_api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:&quot;DefaultMapId&quot;,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), 'my_api_key', defaultMapId: 'DefaultMapId'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12),
        ];
        yield 'with default map id, when passing options (except the "mapId")' => [
            'expected_renderer' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;my_api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:&quot;DefaultMapId&quot;,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), 'my_api_key', defaultMapId: 'DefaultMapId'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->options(new GoogleOptions()),
        ];
        yield 'with default map id overridden by option "mapId"' => [
            'expected_renderer' => '<div data-controller="symfony--ux-google-map--map" data-symfony--ux-google-map--map-provider-options-value="{&quot;apiKey&quot;:&quot;my_api_key&quot;}" data-symfony--ux-google-map--map-center-value="{&quot;lat&quot;:48.8566,&quot;lng&quot;:2.3522}" data-symfony--ux-google-map--map-zoom-value="12" data-symfony--ux-google-map--map-fit-bounds-to-markers-value="false" data-symfony--ux-google-map--map-options-value="{&quot;mapId&quot;:&quot;CustomMapId&quot;,&quot;gestureHandling&quot;:&quot;auto&quot;,&quot;backgroundColor&quot;:null,&quot;disableDoubleClickZoom&quot;:false,&quot;zoomControlOptions&quot;:{&quot;position&quot;:22},&quot;mapTypeControlOptions&quot;:{&quot;mapTypeIds&quot;:[],&quot;position&quot;:14,&quot;style&quot;:0},&quot;streetViewControlOptions&quot;:{&quot;position&quot;:22},&quot;fullscreenControlOptions&quot;:{&quot;position&quot;:20},&quot;@provider&quot;:&quot;google&quot;}" data-symfony--ux-google-map--map-markers-value="[]" data-symfony--ux-google-map--map-polygons-value="[]" data-symfony--ux-google-map--map-polylines-value="[]"></div>',
            'renderer' => new GoogleRenderer(new StimulusHelper(null), 'my_api_key', defaultMapId: 'DefaultMapId'),
            'map' => (new Map())
                ->center(new Point(48.8566, 2.3522))
                ->zoom(12)
                ->options(new GoogleOptions(mapId: 'CustomMapId')),
        ];
    }
}
