<?php

$areas_file = fopen("areas.csv", "r");
while (($area_line = fgetcsv($areas_file)) !== FALSE) {
    $area_fa_name = $area_line[0];
    $area_en_name = $area_line[1];
    $color = $area_line[2];
    $ballon_text = "$area_en_name: $[name] - $area_fa_name: $[description]";

    $xml = xmlwriter_open_uri("areas/$area_en_name.kml");
    xmlwriter_set_indent($xml, 1);
    $res = xmlwriter_set_indent_string($xml, ' ');

    xmlwriter_start_document($xml, '1.0', 'UTF-8');

    xmlwriter_start_element($xml, 'kml');

    xmlwriter_start_attribute($xml, 'xmlns');
    xmlwriter_text($xml, 'http://www.opengis.net/kml/2.2');
    xmlwriter_end_attribute($xml);

    xmlwriter_start_element($xml, 'Document');

    /** Style */
    xmlwriter_start_element($xml, 'Style');
    xmlwriter_start_attribute($xml, 'id');
    xmlwriter_text($xml, $area_en_name);
    xmlwriter_end_attribute($xml);

    /** IconStyle */
    xmlwriter_start_element($xml, 'IconStyle');

    xmlwriter_start_element($xml, 'color');
    xmlwriter_text($xml, 'ffff0000');
    xmlwriter_end_element($xml);

    xmlwriter_start_element($xml, 'scale');
    xmlwriter_text($xml, '1.0');
    xmlwriter_end_element($xml);

    xmlwriter_end_element($xml);
    /** end of IconStyle */

    /** LineStyle */
    xmlwriter_start_element($xml, 'LineStyle');

    xmlwriter_start_element($xml, 'color');
    xmlwriter_text($xml, 'ffff0000');
    xmlwriter_end_element($xml);

    xmlwriter_start_element($xml, 'width');
    xmlwriter_text($xml, '1');
    xmlwriter_end_element($xml);

    xmlwriter_end_element($xml);
    /** end of LineStyle */

    /** PolyStyle */
    xmlwriter_start_element($xml, 'PolyStyle');

    xmlwriter_start_element($xml, 'color');
    xmlwriter_text($xml, $color);
    xmlwriter_end_element($xml);

    xmlwriter_end_element($xml);
    /** end of PolyStyle */

    /** BalloonStyle */
    xmlwriter_start_element($xml, 'BalloonStyle');

    xmlwriter_start_element($xml, 'text');
    xmlwriter_text($xml, $ballon_text);
    xmlwriter_end_element($xml);

    xmlwriter_end_element($xml);
    /** end of BalloonStyle */

    xmlwriter_end_element($xml);
    /** end of Style */

    $countries_file = fopen("area_countries/$area_en_name.csv", "r");
    while (($country_line = fgetcsv($countries_file)) !== FALSE) {
        $country_en_name = $country_line[0];
        $country_fa_name = $country_line[1];

        $country_file = simplexml_load_file("countries_multigeometry/$country_en_name.xml");
        $multi_geometry = $country_file->MultiGeometry->asXML();

        /** Placemark */
        xmlwriter_start_element($xml, 'Placemark');

        xmlwriter_start_element($xml, 'snippet');
        xmlwriter_end_element($xml);

        xmlwriter_start_element($xml, 'name');
        xmlwriter_text($xml, $country_en_name);
        xmlwriter_end_element($xml);

        xmlwriter_start_element($xml, 'description');
        xmlwriter_text($xml, $country_fa_name);
        xmlwriter_end_element($xml);

        xmlwriter_start_element($xml, 'styleUrl');
        xmlwriter_text($xml, "#$area_en_name");
        xmlwriter_end_element($xml);

        xmlwriter_write_raw($xml, $multi_geometry);

        xmlwriter_end_element($xml);
        /** end of Placemark */
    }

    //end of Document
    xmlwriter_end_element($xml);
    //end of kml
    xmlwriter_end_element($xml);
    //end of file
    xmlwriter_end_document($xml);

    echo xmlwriter_output_memory($xml);
}


