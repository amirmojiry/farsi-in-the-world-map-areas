<?php
$xml = simplexml_load_file('UIA_World_Countries_Boundaries.xml');

foreach ($xml->Document->Folder->Placemark as $placemark) {
    foreach ($placemark->ExtendedData->SchemaData->SimpleData as $simpleData) {
        if ($simpleData['name'] == "COUNTRY") {
            $countryName = $simpleData;
        }
    }
    file_put_contents("folder/$countryName.xml", 
    "<kml>".$placemark->MultiGeometry->asXML()."</kml>");
}