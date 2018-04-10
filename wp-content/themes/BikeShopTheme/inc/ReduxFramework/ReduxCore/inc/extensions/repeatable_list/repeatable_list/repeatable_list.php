<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Repeatable list
 * @author      LenLay [resurgent11@gmail.com]
 * @version     1.0.4
 */

// Exit if accessed directly
if ( !defined ( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( !class_exists ( 'ReduxFramework_repeatable_list' ) ) {

    class ReduxFramework_repeatable_list {

        public $_extension_url;
        public $_extension_dir;

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct ( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
            $this->required_fields = array();
            if ( empty( self::$_extension_dir ) ) {
                $this->_extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->_extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->_extension_dir ) );
            }
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render () {
            $defaults = array(
                'accordion' => false,
                'max' => false,
                'min' => false,
                'fixed' => false,
                'items_title' => __( 'Item', 'redux-framework' ),
                'remove_button' => __( 'Delete', 'redux-framework' ),
                'add_button' => __( 'Add new', 'redux-framework' ),
                'fields' => array()
            );
            $this->field = wp_parse_args ( $this->field, $defaults );

            include 'layout.php';
        }

        /**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue () {
            /* Fix Redux enqueue */
            $fix = false;
            $search_fields = array(
                'background',
                'border',
                'dimensions',
                'select',
                'select_image',
                'slider',
                'spacing',
                'typography',
                'color_scheme',
                'css_layout'
            );
            
            foreach ($this->field['fields'] as $registered_field) {
                if(in_array($registered_field['type'], $search_fields)){
                    $fix = true;
                    break;
                }
            }

            if ($fix) {
                wp_register_style(
                    'select2-css',
                    ReduxFramework::$_url . 'assets/js/vendor/select2/select2.css',
                    array(),
                    @filemtime( ReduxFramework::$_dir . 'assets/js/vendor/select2/select2.css' ),
                    'all'
                );

                wp_enqueue_style( 'select2-css' );

                wp_register_script(
                    'select2-sortable-js',
                    ReduxFramework::$_url . 'assets/js/vendor/select2.sortable.min.js',
                    array( 'jquery' ),
                    @filemtime( ReduxFramework::$_dir . 'assets/js/vendor/select2.sortable.min.js' ),
                    true
                );

                wp_register_script(
                    'select2-js',
                    ReduxFramework::$_url . 'assets/js/vendor/select2/select2.min.js',
                    array( 'jquery', 'select2-sortable-js' ),
                    @filemtime( ReduxFramework::$_dir . 'assets/js/vendor/select2/select2.min.js' ),
                    true
                );

                wp_enqueue_script( 'select2-js' );
            }

            $search_fields = array(
                'background',
                'color',
                'color_gradient',
                'link_color',
                'border',
                'typography',
                'css_layout'
            );

            $fix = false;
            foreach ($this->field['fields'] as $registered_field) {
                if(in_array($registered_field['type'], $search_fields)){
                    $fix = true;
                    break;
                }
            }

            if ($fix) {
                wp_enqueue_style(
                    'redux-color-picker-css',
                    ReduxFramework::$_url . 'assets/css/color-picker/color-picker.css',
                    array( 'wp-color-picker' ),
                    @filemtime( ReduxFramework::$_dir . 'assets/css/color-picker/color-picker.css' ),
                    'all'
                );

                wp_enqueue_style( 'color-picker-css' );


                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_style( 'wp-color-picker' );
            }

            /* End Fix Redux enqueue */

            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_style( 'jquery-ui-sortable' );

            wp_enqueue_script (
                'redux-field-repeatable-list-js', 
                $this->_extension_url . 'repeatable_list' . /*Redux_Functions::isMin () .*/ '.js', 
                array( 'jquery', 'jquery-ui-core' ), 
                time (), 
                true
            );

            wp_enqueue_style (
                'redux-field-repeatable-list-css', 
                $this->_extension_url . 'repeatable_list.css', 
                time (), 
                true
            );
        }

        public function ll_render_fields($index = 0) {
            $row_class = ($this->field['accordion'])? ' accordion' : '';
            echo '<div class="repeatable-row' . $row_class . '" data-index="' . $index . '">';
                if($this->field['accordion']){
                    echo '<div class="row-head">';
                        echo '<span class="sortable-key"></span>';
                        echo '<h3 class="row-title">';
                            echo $this->field['items_title'] . ' &#35;';
                            echo $index+1;
                            echo '<span class="dashicons dashicons-arrow-down row-toggle"></span>';
                        echo '</h3>';
                    echo '</div>';
                    echo '<div class="row-content" style="display: none;">';
                } else {
                    echo '<h3 class="row-title">';
                        echo $this->field['items_title'] . ' &#35;';
                        echo $index+1;
                    echo '</h3>';
                    echo '<div class="row-content">';
                    if(!$this->field['fixed'])
                        echo '<span class="dashicons dashicons-no-alt delete-button"></span>';
                }

                foreach ($this->field['fields'] as $field) {
                    $field_class = (isset($field['type']))? 'ReduxFramework_' . $field['type'] : '';
                    $value = (is_array($this->value) && isset($this->value[$index][$field['id']]))? $this->value[$index][$field['id']] : '';
                    if($value == '' && isset($field['default']))
                        $value = $field['default'];

                    if(!isset($field['class']))
                        $field['class'] = '';
                    
                    $attributes = '';
                    $css_classes = '';

                    if(isset($field['required'])){
                        $css_classes .= ' required-field';

                        if(!$this->_ll_can_output($field, $index)){
                            $css_classes .= ' hide';
                        }
                        $attributes .= ' data-required="'.base64_encode(json_encode($this->required_fields[$index])).'"';

                    }
                    ?>
                    <div class="ll-field-container<?php echo $css_classes; ?>"<?php echo $attributes; ?>>
                        <?php if(isset($field['title']) && !empty($field['title'])){ ?>
                            <h4 class="field-title"><?php echo $field['title']; ?></h4>
                        <?php }

                        $field['name'] = $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $index . '][' . $field['id'] . ']';

                        $field['id'] = $field['id'] . '-' . $index;

                        $this->parent->_field_input( $field, $value, $this->parent );
                        if ( class_exists( $field_class ) ) {
                            $field_obj = new $field_class( $field, '', $this->parent );

                            if(method_exists($field_obj, 'enqueue'))
                                $field_obj->enqueue();

                            if(method_exists($field_obj, 'output')){
                                $field_obj->output();
                            }
                            

                        } ?>
                    </div>
                    <?php
                }
                    $sort = (is_array($this->value) && isset($this->value[$index]['ll-row-sort']))? $this->value[$index]['ll-row-sort'] : '';
                    if($this->field['accordion'] && !$this->field['fixed']){
                        echo '<div class="button-controls">';
                            echo '<button class="button button-secondary delete-button">'. $this->field['remove_button'] .'</button>';
                        echo '</div>';
                    }
                    echo '<input type="hidden" class="ll-row-sort" value="'. $sort .'" name="'.$this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $index . '][ll-row-sort]'.'">';
                echo '</div>';
            echo '</div>';
        }

        public function ll_render_template($index = 0) {
            $row_class = ($this->field['accordion'])? ' accordion' : '';
            echo '<div class="repeatable-row' . $row_class . '" data-index="' . $index . '">';
                if($this->field['accordion']){
                    echo '<div class="row-head">';
                        echo '<span class="sortable-key"></span>';
                        echo '<h3 class="row-title">';
                            echo $this->field['items_title'] . ' &#35;';
                            echo $index+1;
                            echo '<span class="dashicons dashicons-arrow-down row-toggle"></span>';
                        echo '</h3>';
                    echo '</div>';
                    echo '<div class="row-content" style="display: none;">';
                } else {
                    echo '<h3 class="row-title">';
                        echo $this->field['items_title'] . ' &#35;';
                        echo $index+1;
                    echo '</h3>';
                    echo '<div class="row-content">';
                    if(!$this->field['fixed'])
                        echo '<span class="dashicons dashicons-no-alt delete-button"></span>';
                }

                foreach ($this->field['fields'] as $field) {
                    $field_class = (isset($field['type']))? 'ReduxFramework_' . $field['type'] : '';
                    $value = (is_array($this->value) && isset($this->value[$index][$field['id']]))? $this->value[$index][$field['id']] : '';
                    if($value == '' && isset($field['default']))
                        $value = $field['default'];

                    if(!isset($field['class']))
                        $field['class'] = '';
                    
                    $attributes = '';
                    $css_classes = '';

                    if(isset($field['required'])){
                        $css_classes .= ' required-field';

                        if(!$this->_ll_can_output($field, $index)){
                            $css_classes .= ' hide';
                        }
                        $attributes .= ' data-required="'.base64_encode(json_encode($this->required_fields[$index])).'"';

                    }
                    ?>
                    <div class="ll-field-container<?php echo $css_classes; ?>"<?php echo $attributes; ?>>
                        <?php if(isset($field['title']) && !empty($field['title'])){ ?>
                            <h4 class="field-title"><?php echo $field['title']; ?></h4>
                        <?php }

                        $field['name'] = $this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $index . '][' . $field['id'] . ']';

                        $field['id'] = $field['id'] . '-' . $index;

                        $this->parent->_field_input( $field, $value, $this->parent );
                        if ( class_exists( $field_class ) ) {
                            $field_obj = new $field_class( $field, '', $this->parent );

                            if(method_exists($field_obj, 'enqueue'))
                                $field_obj->enqueue();

                            if(method_exists($field_obj, 'output')){
                                $field_obj->output();
                            }
                            

                        } ?>
                    </div>
                    <?php
                }
                    $sort = (is_array($this->value) && isset($this->value[$index]['ll-row-sort']))? $this->value[$index]['ll-row-sort'] : '';
                    if($this->field['accordion'] && !$this->field['fixed']){
                        echo '<div class="button-controls">';
                            echo '<button class="button button-secondary delete-button">'. $this->field['remove_button'] .'</button>';
                        echo '</div>';
                    }
                    echo '<input type="hidden" class="ll-row-sort" value="'. $sort .'" name="'.$this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $index . '][ll-row-sort]'.'">';
                echo '</div>';
            echo '</div>';
        }

        private function _renter_template($index) {
            echo '<script type="text/template" class="repeatable-row-template">';
            ob_start();
            $this->ll_render_fields($index);
            echo ob_get_clean();
            echo '</script>';
        }

        /**
         * Can Output CSS
         * Check if a field meets its requirements before outputting to CSS
         *
         * @param $field
         *
         * @return bool
         */
        private function _ll_can_output( $field, $index ) {
            $return = true;

            if ( isset( $field['force_output'] ) && $field['force_output'] == true ) {
                return $return;
            }

            if ( ! empty( $field['required'] ) ) {
                if ( isset( $field['required'][0] ) ) {
                    if ( ! is_array( $field['required'][0] ) && count( $field['required'] ) == 3 && isset($GLOBALS[ $this->parent->args['global_variable'] ]['list-items'][ $index ]) ) {
                        $parentValue = $GLOBALS[ $this->parent->args['global_variable'] ]['list-items'][ $index ][ $field['required'][0] ];
                        $checkValue  = $field['required'][2];
                        $operation   = $field['required'][1];
                        $this->required_fields[$index] = array('index' => $index, 'required' => $field['required'][0], 'checkValue' => $checkValue, 'operation' => $operation);
                        $return      = $this->compareValueDependencies( $parentValue, $checkValue, $operation );
                    } else if ( is_array( $field['required'][0] ) && isset($GLOBALS[ $this->parent->args['global_variable'] ]['list-items'][ $index ]) ) {
                        foreach ( $field['required'] as $required ) {
                            if ( ! is_array( $required[0] ) && count( $required ) == 3 ) {
                                $parentValue = $GLOBALS[ $this->parent->args['global_variable'] ]['list-items'][ $index ][ $required[0] ];
                                $checkValue  = $required[2];
                                $operation   = $required[1];
                                $this->required_fields[$index] = array('index' => $index, 'required' => $required[0], 'checkValue' => $checkValue, 'operation' => $operation);
                                $return      = $this->compareValueDependencies( $parentValue, $checkValue, $operation );
                            }
                            if ( ! $return ) {
                                return $return;
                            }
                        }
                    } else {
                        if ( ! is_array( $field['required'][0] ) && count( $field['required'] ) == 3 ) {
                            $checkValue  = $field['required'][2];
                            $operation   = $field['required'][1];
                            $this->required_fields[$index] = array('index' => $index, 'required' => $field['required'][0], 'checkValue' => $checkValue, 'operation' => $operation);
                        } 
                        return false;
                    }
                }
            }
            return $return;
        } // _can_output_css

        // Compare data for required field
        private function compareValueDependencies( $parentValue, $checkValue, $operation ) {
            $return = false;

            switch ( $operation ) {
                case '=':
                case 'equals':
                    $data['operation'] = "=";
                    if ( is_array( $checkValue ) ) {
                        if ( in_array( $parentValue, $checkValue ) ) {
                            $return = true;
                        }
                    } else {
                        if ( $parentValue == $checkValue ) {
                            $return = true;
                        } else if ( is_array( $parentValue ) ) {
                            if ( in_array( $checkValue, $parentValue ) ) {
                                $return = true;
                            }
                        }
                    }
                    break;
                case '!=':
                case 'not':
                    $data['operation'] = "!==";
                    if ( is_array( $checkValue ) ) {
                        if ( ! in_array( $parentValue, $checkValue ) ) {
                            $return = true;
                        }
                    } else {
                        if ( $parentValue != $checkValue ) {
                            $return = true;
                        } else if ( is_array( $parentValue ) ) {
                            if ( ! in_array( $checkValue, $parentValue ) ) {
                                $return = true;
                            }
                        }
                    }
                    break;
                case '>':
                case 'greater':
                case 'is_larger':
                    $data['operation'] = ">";
                    if ( $parentValue > $checkValue ) {
                        $return = true;
                    }
                    break;
                case '>=':
                case 'greater_equal':
                case 'is_larger_equal':
                    $data['operation'] = ">=";
                    if ( $parentValue >= $checkValue ) {
                        $return = true;
                    }
                    break;
                case '<':
                case 'less':
                case 'is_smaller':
                    $data['operation'] = "<";
                    if ( $parentValue < $checkValue ) {
                        $return = true;
                    }
                    break;
                case '<=':
                case 'less_equal':
                case 'is_smaller_equal':
                    $data['operation'] = "<=";
                    if ( $parentValue <= $checkValue ) {
                        $return = true;
                    }
                    break;
                case 'contains':
                    if ( strpos( $parentValue, $checkValue ) !== false ) {
                        $return = true;
                    }
                    break;
                case 'doesnt_contain':
                case 'not_contain':
                    if ( strpos( $parentValue, $checkValue ) === false ) {
                        $return = true;
                    }
                    break;
                case 'is_empty_or':
                    if ( empty( $parentValue ) || $parentValue == $checkValue ) {
                        $return = true;
                    }
                    break;
                case 'not_empty_and':
                    if ( ! empty( $parentValue ) && $parentValue != $checkValue ) {
                        $return = true;
                    }
                    break;
                case 'is_empty':
                case 'empty':
                case '!isset':
                    if ( empty( $parentValue ) || $parentValue == "" || $parentValue == null ) {
                        $return = true;
                    }
                    break;
                case 'not_empty':
                case '!empty':
                case 'isset':
                    if ( ! empty( $parentValue ) && $parentValue != "" && $parentValue != null ) {
                        $return = true;
                    }
                    break;
            }

            return $return;
        }
    }
}