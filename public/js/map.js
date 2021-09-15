// Fonction d'initialisation de la carte
    function initMap(lat, lon)
{
    // Create object "myMap" et l'insèrer dans l'élément HTML qui a l'ID "map"
    myMap = L.map(map).setView([lat, lon], 15);
    // recover tiles from  openstreetmap.fr
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        //lien vers la source des données
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 30
    }).addTo(myMap);
    var marker = L.marker([lat, lon]).addTo(myMap);
};

