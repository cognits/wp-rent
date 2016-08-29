<?php

if( !function_exists('wpestate_fields_type_select') ):
    function wpestate_fields_type_select($real_value){

        $select = '<select id="field_type" name="add_field_type[]" style="width:140px;">';
        $values = array('short text','long text','numeric','date');

        foreach($values as $option){
            $select.='<option value="'.$option.'"';
                if( $option == $real_value ){
                     $select.= ' selected="selected"  ';
                }       
            $select.= ' > '.$option.' </option>';
        }   
        $select.= '</select>';
        return $select;
    }
endif; // end   wpestate_fields_type_select  






if( !function_exists('wpestate_custom_fields') ):
    function wpestate_custom_fields(){

        $custom_fields = get_option( 'wp_estate_custom_fields', true);     
        $current_fields='';


        $i=0;
        if( !empty($custom_fields)){    
            while($i< count($custom_fields) ){
                $current_fields.='
                    <div class=field_row>
                    <div    class="field_item"><strong>'.esc_html__( 'Field Name','wpestate').'</strong></br><input  type="text" name="add_field_name[]"   value="'.stripslashes( $custom_fields[$i][0] ).'"  ></div>
                    <div    class="field_item"><strong>'.esc_html__( 'Field Label','wpestate').'</strong></br><input  type="text" name="add_field_label[]"   value="'.stripslashes( $custom_fields[$i][1]).'"  ></div>
                    <div    class="field_item"><strong>'.esc_html__( 'Field Type','wpestate').'</strong></br>'.wpestate_fields_type_select($custom_fields[$i][2]).'</div>
                    <div    class="field_item"><strong>'.esc_html__( 'Field Order','wpestate').'</strong></br><input  type="text" name="add_field_order[]" value="'.$custom_fields[$i][3].'"></div>     
                    <a class="deletefieldlink" href="#">'.esc_html__( 'delete','wpestate').'</a>
                </div>';    
                $i++;
            }
        }

        print '<div class="wpestate-tab-container">';
        print '<h1 class="wpestate-tabh1">'.esc_html__( 'Custom Fields','wpestate').'</h1>';  
        print' <form method="post" action="">

            <div id="custom_fields">
            '.$current_fields.'
            <input type="hidden" name="is_custom" value="1">   
            </div>

            <h3 style="margin-left:10px;">'.esc_html__( 'Add New Custom Field','wpestate').'</h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <tr valign="top">
                            <th scope="row">'.esc_html__( 'Field name','wpestate').'</th>
                            <td>
                                <input  type="text" id="field_name"  name="field_name"   value="" size="40"/>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">'.esc_html__( 'Field Label','wpestate').'</th>
                            <td>
                                <input  type="text" id="field_label"  name="field_label"   value="" size="40"/>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">'.esc_html__( 'Field Type','wpestate').'</th>
                            <td>
                                <select id="field_type" name="field_type"  style="width:236px;">
                                    <option value="short text"> short text  </option>
                                    <option value="long text">  long text   </option>
                                    <option value="numeric">    numeric     </option>
                                    <option value="date">       date        </option>
                                </select>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">'.esc_html__( ' Order in listing page','wpestate').'</th>
                            <td>
                                 <input  type="text" id="field_order"  name="field_order"   value="" size="40"/>
                            </td>
                        </tr>   
                </tbody>
            </table>   

           <a href="#" id="add_field">'.esc_html__( ' click to add field','wpestate').'</a><br>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button-primary" style="margin-left:10px;" value="'.esc_html__( 'Save Changes','wpestate').'" />
            </p>

        </form> 
        </div> 
        ';
    }
endif; // end   wpestate_custom_fields  










if( !function_exists('wpestate_display_features') ):
    function wpestate_display_features(){
        $feature_list                           =   esc_html( get_option('wp_estate_feature_list') );
        $feature_list                           =   str_replace(', ',',&#13;&#10;',$feature_list);
        $feature_list                           =   stripslashes(  $feature_list    );
        $cache_array=array('yes','no');
        $show_no_features_symbol='';
        $show_no_features= esc_html ( get_option('wp_estate_show_no_features','') );

        foreach($cache_array as $value){
                $show_no_features_symbol.='<option value="'.$value.'"';
                if ($show_no_features==$value){
                        $show_no_features_symbol.=' selected="selected" ';
                }
                $show_no_features_symbol.='>'.$value.'</option>';
        }


        print '<div class="wpestate-tab-container">';
        print '<h1 class="wpestate-tabh1">Listings Features & Amenities</h1>';  
        print '
        <form method="post" action="">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row">Add New Element in Features and Amenities </th>
                        <td>
                            <input  type="text" id="new_feature"  name="new_feature"   value="type here feature name.. " size="40"/><br>
                            <a href="#" id="add_feature"> click to add feature </a><br>
                            <textarea id="feature_list" name="feature_list" rows="15" cols="42">'. $feature_list.'</textarea>  
                        </td>

                    </tr>


                    <tr valign="top">
                        <th scope="row">Show the Features and Amenities that are not available </th>
                        <td>
                            <select id="show_no_features" name="show_no_features">
                                '.$show_no_features_symbol.'
                            </select>
                        </td>
                    </tr>

                </tbody>
            </table>   

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button-primary" style="margin-left:10px;" value="Save Changes" />
            </p>

        </form> 
        </div> 
        ';
    }
endif; // end   wpestate_display_features  






if( !function_exists('wpestate_display_labels') ):
    function wpestate_display_labels(){
        $cache_array                            =   array('yes','no');
        $status_list                            =   esc_html( get_option('wp_estate_status_list') );
        $status_list                            =   str_replace(', ',',&#13;&#10;',$status_list);
        $status_list                            =   stripslashes($status_list);
        $property_adr_text                      =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
        $property_description_text              =   stripslashes ( esc_html( get_option('wp_estate_property_description_text') ) );
        $property_details_text                  =   stripslashes ( esc_html( get_option('wp_estate_property_details_text') ) );
        $property_features_text                 =   stripslashes ( esc_html( get_option('wp_estate_property_features_text') ) );
        $property_price_text                    =   stripslashes ( esc_html( get_option('wp_estate_property_price_text') ) );
        $property_pictures_text                 =   stripslashes ( esc_html( get_option('wp_estate_property_pictures_text') ) );


        print '<div class="wpestate-tab-container">';
        print '<h1 class="wpestate-tabh1">'.esc_html__( 'Listings Labels','wpestate').'</h1>';    
        print '
        <form method="post" action="">
            <table class="form-table">
                <tbody>

                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Property Adress Label','wpestate').'</th>
                        <td>
                            <input  type="text" id="property_adr_text"  name="property_adr_text"   value="'.$property_adr_text.'" size="40"/>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Property Features Label','wpestate').'</th>
                        <td>
                            <input  type="text" id="property_features_text"  name="property_features_text"   value="'.$property_features_text.'" size="40"/>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Property Description Label','wpestate').'</th>
                        <td>
                            <input  type="text" id="property_description_text"  name="property_description_text"   value="'.$property_description_text.'" size="40"/>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Property Details Label','wpestate').'</th>
                        <td>
                            <input  type="text" id="property_details_text"  name="property_details_text"   value="'.$property_details_text.'" size="40"/>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Property Price Label','wpestate').'</th>
                        <td>
                            <input  type="text" id="property_price_text"  name="property_price_text"   value="'.$property_price_text.'" size="40"/>
                        </td>
                    </tr>
                    
                    <!--
                    <tr valign="top">
                        <th scope="row">'.esc_html__( 'Property Pictures Label','wpestate').'</th>
                        <td>
                            <input  type="text" id="property_pictures_text"  name="property_pictures_text"   value="'.$property_pictures_text.'" size="40"/>
                        </td>
                    </tr> 
                    -->
                    <th scope="row">Property Status (* you may need to add new css classes - please see the help files) </th>
                    <td>
                        <input  type="text" id="new_status"  name="new_status"   value="type here the new status... " size="40"/>
                        <a href="#new_status" id="add_status"> click to add new status </a><br>
                        <textarea id="status_list" name="status_list" rows="7" cols="42">'.$status_list.'</textarea>  
                    </td>
                    </tr>
                




                </tbody>
            </table>   



            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button-primary" style="margin-left:10px;" value="Save Changes" />
            </p>

        </form> 
        </div> 
        ';
    }
endif; // end   wpestate_display_labels  
?>