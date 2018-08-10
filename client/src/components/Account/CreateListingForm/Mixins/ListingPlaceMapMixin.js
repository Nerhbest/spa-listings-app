import ymaps from "ymaps";

let maps = null;

export const ListingPlaceMapMixin = {
    data: () => ({
        placeMap: null,
        isMapLoaded: false,
        placemark: null,
        isGeocoderLoading: false
    }),
    methods: {
        isMapAlreadyLoaded()
        {
            return this.isMapLoaded == true;
        },
        loadMap(mapElement, placeInfo,cb)
        {
            this.isMapLoaded = true;
            ymaps.load('https://api-maps.yandex.ru/2.1/?lang=ru_RU').then(ymaps => {
                maps = ymaps;
                this.placeMap = new maps.Map(mapElement, {
                    center: [placeInfo.lat, placeInfo.lng],
                    zoom: 15
                })
                this.makePlaceMark(placeInfo)
                cb(placeInfo);
                this.setEvents();
            });

        },
        makePlaceMark(placeInfo)
        {
            if(this.isPlacemarkExist())
                this.resetPlacemark();


            this.placemark = new maps.Placemark([placeInfo.lat, placeInfo.lng], false);
            this.placeMap.geoObjects.add(this.placemark);
        },
        isPlacemarkExist()
        {
          return this.placemark !== null;
        },
        resetPlacemark()
        {
            this.placemark = null;
        },
        updateCoordsInMap(placeInfo)
        {
            if(!this.isMapAlreadyLoaded()) throw new Error("Yandex Cart was not loaded");

            this.placeMap.setCenter([placeInfo.lat,placeInfo.lng], 15);
            this.makePlaceMark(placeInfo);
        },
        replacePlacemark(coords)
        {
            this.placemark.geometry.setCoordinates(coords);
        },
        setEvents()
        {
            if(!this.isMapAlreadyLoaded()) throw new Error("Yandex Cart was not loaded");
            this.placeMap.events.add('click', (e) => {
                let coords = e.get('coords');
                this.getPlaceName(coords);
            })
        },
        getPlaceName(coords)
        {
            this.isGeocoderLoading = true;
            maps.geocode(coords).then(resp => {
                this.isGeocoderLoading = false;
                let geoObject = resp.geoObjects.get(0);
                let latLngPair = coords.map(coord => +coord.toFixed(6));
                let placeInfo = {
                    lat: latLngPair[0],
                    lng: latLngPair[1],
                    name: geoObject.getAddressLine()
                };

                this.setPlaceInfo(placeInfo);
                this.replacePlacemark(coords);
            })
        }
    }
};