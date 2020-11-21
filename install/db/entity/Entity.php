<?php

if(!isset($_SERVER['argv'][1]) || (!$action = $_SERVER['argv'][1])) {
	$action = 'add';
}
$actions = array('add', 'delete', 'edit', 'link');
if(!in_array($action, $actions)) {
	#echo("Unknown action! Setting action to adding mode!\n");
	$action = 'add';
}

#
# Data
#
$table_properties = array(
    array('name'=>'name_field',
          'prompt'=>"Name field for %s [name]: ",
          'value'=>'name',
    ),
    array('name'=>'is_tree',
          'prompt' =>"Is %s a tree? ([0]/1): ",
          'value'=>0,
    ),
    array('name'=>'order_by',
          'prompt' =>"Default order by? [id]: ",
          'value'=>'id',
    ),
    array('name'=>'order_dir',
          'prompt' =>"Default order dir? [desc]: ",
          'value'=>'desc',
    )
);
$db_types = array(
	'Main types' => array(
		'string', 
		'text', 
		'boolean', 
		'integer', 
		'smallint', 
		'bigint', 
		'float',
	),
	'Array/Object Types' => array(
		'array', 
		'json', 
		'object', 
		'binary', 
		'blob',
	),
	'Date/Time Types' => array(
		'datetime', 
		'datetime_immutable', 
		'datetimetz', 
		'datetimetz_immutable', 
		'date', 
		'date_immutable',
		'time', 
		'time_immutable', 
		'dateinterval',
	),
	'Other Types' => array(
		'decimal', 
		'guid', 
		'json_array',
	),
);
$form_types = array(
    'UI' => array(
        'UIFileType',
    ),
    
	'Text Fields' => array(
	    'TextType',
	    'TextareaType',
	    'EmailType',
	    'IntegerType',
	    'MoneyType',
	    'NumberType',
	    'PasswordType',
	    'PercentType',
	    'SearchType',
	    'UrlType',
	    'RangeType',
	    'TelType',
	    'ColorType',
	),
	'Choice Fields' => array(
	    'ChoiceType',
	    'EntityType',
	    'CountryType',
	    'LanguageType',
	    'LocaleType',
	    'TimezoneType',
	    'CurrencyType',
	),
	'Date and Time Fields' => array(
	    'DateType',
	    'DateIntervalType',
	    'DateTimeType',
	    'TimeType',
	    'BirthdayType',
	    'WeekType',
	),
	'Other Fields' => array(
	    'CheckboxType',
	    'FileType',
	    'RadioType',
	),
	'Field Groups' => array(
	    'CollectionType',
	    'RepeatedType',
	),
	'Hidden Fields' => array(
	    'HiddenType',
	),
	'Buttons' => array(
	    'ButtonType',
	    'ResetType',
	    'SubmitType',
	),
	'Base Fields' => array(
	    'FormType',
	),
);
$types_with_length = array('string', 'text');
$types_translatables = array('string', 'text');

$types_exclude_form_type = array('EntityType');
$nullable_exclude_form_type = array('EntityType');
$translatable_exclude_form_type = array('EntityType');
$length_exclude_form_type = array('EntityType');

define('PREG_FIELD_PATTERN', '"/[\s\*]+?@ORM([^\{]*?)(private|protected)(.+?)(\$)([a-zA-Z0-9_-]+?)( *\=.+?;|;)"s');
define('PREG_FIELD_REPLACE', '"/[\s\*]+?@ORM([^\{]*?)(private|protected)(.+?)(\$)(%s)( *\=.+?;|;)"s');

#
# UI config
#
$config_file = 'config/packages/ui.yaml';
if(!file_exists('config/packages/ui.yaml')) {
    echo "Config file 'config/packages/ui.yaml' not found!";
    exit();
}
$config = yaml_parse_file($config_file);
$ui_config = $config['parameters']['ui_config'];


#
# Get table name
#
$setting_table_name = true;
while($setting_table_name==true) {
    if($table_name = trim(readline("Table name : "))) {
        
        $entity_name = str_replace('_', '', ucwords($table_name, '_'));
        $full_entity_name = "App\Entity\\$entity_name";
        
        if(isset($ui_config['entity'][$full_entity_name]) && $action=='add') {
            echo("The table $table_name already exists so add fields!\n");
			$action = 'edit';
			$setting_table_name = false;
        } else if($action == 'link' && $table_name == 'user'){
            echo("The table 'user' can't be linked!\n");
        } else {
            $setting_table_name = false; 
        }
    }
}

$entity_file = 'src/Entity/' . $entity_name . '.php';
$translation_file = 'src/Entity/' . $entity_name . 'Translation.php';


# DELETE TABLE
if($action == 'delete') {
    $setting_confirm_deleting = true;
    while($setting_confirm_deleting==true) {
        $confirm_deleting = trim(readline("Confirm deleting table $table_name [n]/y:"));
        if($confirm_deleting == 'y') {
            
            echo("Deleting $table_name.\n");
            
            if($ui_config['entity'][$full_entity_name]) {
                # Remove from config
                unset($ui_config['entity'][$full_entity_name]);
                foreach($ui_config['admin']['pages'] as $i => $cms_page) {
                    if(isset($cms_page['arguments']['entity_name']) && $cms_page['arguments']['entity_name'] == $full_entity_name) {
                        unset($ui_config['admin']['pages'][$i]);
                    }
                }
                
                # Delete files
                if(file_exists($entity_file)) {
                    unlink($entity_file);
                }
                if(file_exists($translation_file)) {
                    unlink($translation_file);
                }
            } else {
                echo("Table $table_name does not exist!\n");
            }
            
        } else {
            echo("Aborting deleting...\n");
        }
        
        $setting_confirm_deleting = false;
    }
}

# LINK TABLE
if($action == 'link') {
	
    # Linked table
	$setting_linked_table_name = true;
	while($setting_linked_table_name==true) {
	    if($linked_table_name = trim(readline("Link $table_name to:"))) {
            
            $linked_entity_name = str_replace('_', '', ucwords($linked_table_name, '_'));
            $full_linked_entity_name = "App\Entity\\$linked_entity_name";
            
	        if($linked_table_name == 'user'){
                echo("The table 'user' can't be linked!\n");
            } else if(isset($ui_config['entity'][$full_linked_entity_name])) {
				$setting_linked_table_name = false;
	        } else {
	            echo("$linked_table_name does not exist!\n");
	        }
	    }
	}
    #$linked_entity_name = str_replace('_', '', ucwords($linked_table_name, '_'));
    $linked_entity_file = 'src/Entity/' . $linked_entity_name . '.php';
    $linked_translation_file = 'src/Entity/' . $linked_entity_name . 'Translation.php';
    
	
	# Link table
	$link_table_translatable = 'n';
    $add_translatable = true;
	while($add_translatable == true) {
		$link_table_translatable = trim(readline("Is link table translatable? ([y]/n):"));
		if(!$link_table_translatable) $link_table_translatable = 'y';
		if($link_table_translatable == 'y' || $link_table_translatable == 'n') {
            $link_table_translatable = $link_table_translatable == 'y' ? true : false;
			$add_translatable = false;
		}
	}
    $link_table_name = 'link_' . $table_name . '_' . $linked_table_name;
    $link_entity_name = str_replace('_', '', ucwords($link_table_name, '_'));
    $full_link_entity_name = "App\Entity\\$link_entity_name";
    $link_entity_file = 'src/Entity/' . $link_entity_name . '.php';
    $link_translation_file = $link_table_translatable ? 'src/Entity/' . $link_entity_name . 'Translation.php' : '';
	
	
    # Add link table in list of tables
	$link_table_config = array(
	    'table_name'=> $link_table_name,
	    'name'=> $full_linked_entity_name,
        'name_field'=>'id',
        'order_by'=>'id',
        'order_dir'=>'id',
        'link'=>array($full_entity_name, $full_linked_entity_name),
	);
	$ui_config['entity'][$full_link_entity_name] = $link_table_config;

	
	#
	# Create files
	#
    createEntityFiles($link_table_name, $link_entity_name);
    
    
	#
	# Create link column to both entities
	#
	addFieldToEntityFile($entity_file, $translation_file, false,
        "/**
	 * @ORM\OneToMany(targetEntity=\"" . $full_link_entity_name . "\", mappedBy=\"" . $table_name . "\")
	 */
	private \$" . $link_table_name . ";"
    );
	addFieldToEntityFile($linked_entity_file, $linked_translation_file, false,
        "/**
	 * @ORM\OneToMany(targetEntity=\"" . $full_link_entity_name . "\", mappedBy=\"" . $linked_table_name . "\")
	 */
	private \$" . $link_table_name . ";"
    );
	
	#
	# Add link columns to link entity
	#
	addFieldToEntityFile($link_entity_file, $link_translation_file, false,
        "/**
        	 * @ORM\ManyToOne(targetEntity=\"" . $full_entity_name . "\", inversedBy=\"" . $link_table_name . "\")
        	 * @ORM\JoinColumn(name=\"" . $table_name . "_id\", referencedColumnName=\"id\")
        	 */
        	private \$" . $table_name . ";"
    );
    addFieldToEntityFile($link_entity_file, $link_translation_file, false,
        "/**
        	 * @ORM\ManyToOne(targetEntity=\"" . $full_linked_entity_name . "\", inversedBy=\"" . $link_table_name . "\")
        	 * @ORM\JoinColumn(name=\"" . $linked_table_name . "_id\", referencedColumnName=\"id\")
        	 */
        	private  \$" . $linked_table_name . ";"
    );
}

# ADD TABLE
if($action == 'add') {

	#
	# Set table config 
	#
	$table_config = array(
	    'table_name'=>$table_name,
	    'name'=>$full_entity_name,
	);
	foreach($table_properties as $i=>$property) {
	    $value = trim(readline(sprintf($property['prompt'], $table_name)));
	    $table_config[$property['name']] = !$value ? $property['value'] : $value;
	}
	$ui_config['entity'][$full_entity_name] = $table_config;

	
	#
	# Create files
	#
	createEntityFiles($table_name, $entity_name);
    
    
	#
	# Add to the menu of the CMS
	#
	$has_cms = trim(readline("Add to CMS ([y]/n) ?"));
	if($has_cms == 'y' || !$has_cms) {
	
		#
		# Add a page to the menu for the new entity
		#
		$slug = '';
		while($slug=='') {
			$tmp = readline("CMS page slug [" . $table_name . "s] : ");
			if(!$tmp) $tmp = $table_name . 's';
			if(!isset($ui_config['admin']['pages'][$tmp])) {
				$slug = $tmp;
			} else {
				echo("This slug [$tmp] already exists!\n");
			}
		}
	
		$ui_config['admin']['pages'][$slug] = array(
			'controller' => 'Uicms\Admin\Controller\Editor',
		    'action' => 'index',
		    'slug' => $slug,
		    'arguments' => array(
				'entity_name' => $full_entity_name),
		    'params' =>  array(),
		);
	}
}


# ADD/EDIT FIELDS
if($action == 'add' || $action=='edit') {
    
    $entity_file_content = file_get_contents($entity_file);
    $translation_file_content = file_exists($translation_file) ? file_get_contents($translation_file) : '';
    
	if(!isset($is_table_translatable)) {
		$is_table_translatable = file_exists('src/Entity/' . $entity_name . 'Translation.php');
	}
	$adding = true;
	while($adding == true) {
        $field_mode = 'add';
		$field_name = readline("New field name (<return> to stop adding fields): ");
        
		if(trim($field_name)) {
            
			if(fieldExists($field_name, $entity_file_content) || fieldExists($field_name, $translation_file_content)) {
               	$field_mode = trim(readline("Field [$field_name] exists! Do you want to edit or delete it? ([edit]/delete)"));
                if($field_mode == 'delete') {
                    $is_translatable = fieldExists($field_name, $translation_file_content) ? true : false;
                    deleteFieldFromEntityFile($field_name, $entity_file, $translation_file, $is_translatable);
                    if(isset($ui_config['entity'][$full_entity_name]['form']['fields'][$field_name])) {
                        unset($ui_config['entity'][$full_entity_name]['form']['fields'][$field_name]);
                    } else if(isset($ui_config['entity'][$full_entity_name]['form']['translations'][$field_name])) {
                        unset($ui_config['entity'][$full_entity_name]['form']['translations'][$field_name]);
                    }
                    print "Field '$field_name' deleted!\n";
                } else {
                    $field_mode = 'edit';
                }
            }
			
            if($field_mode == 'edit' || $field_mode == 'add') {
                
                # Default
                $default_transformer = '';
                $default_form_type = 'TextType';
                $default_entity_namespace = 'Symfony\Component\Form\Extension\Core\Type';
                $default_type = 'string';
                $default_length = 255;
                
                # Set field form config
                $form_config = array(
                    'name' => str_replace('_', '', ucwords($field_name, '_')),
                    'type' => $default_form_type,
                    'namespace' => $default_entity_namespace,
                    'transformer' => '',
                    'options' => array(
                        'help' => '',
                        'label' => '',
                        'attr' => array(
                            'class'=>'',
                        ),
                    	'required' => false,
                    ),
                );


    			# Form type (object)
    			$add_form = true;
    			while($add_form == true) {
    				$field_form_type = trim(readline("Form object (enter ? to see all object) [$default_form_type]:"));
    				if($field_form_type=='?') {
    					print_r($form_types);
    				} else {
    					if(!$field_form_type) $field_form_type = $default_form_type;
                        if(strpos($field_form_type, 'Type') === false) $field_form_type .= 'Type';
    					if(inArray($field_form_type, $form_types)) {
    						$add_form = false;
                            $form_config['type'] = $field_form_type;
    					}
    				}
    			}

                # Type
                if(!in_array($field_form_type, $types_exclude_form_type)) {
        			$add_type = true;
        			while($add_type == true) {
        				$field_type = trim(readline("Field type (enter ? to see all types) [$default_type]:"));
        				if($field_type=='?') {
        					print_r($db_types);
        				} else {
        					if(!$field_type) $field_type = $default_type;
        					if(inArray($field_type, $db_types)) {
        						$add_type = false;
        					}
        				}
        			}
                }
    			

    			# Translatable
                $field_translatable = false;
                if($is_table_translatable && isset($field_type) && in_array($field_type, $types_translatables) && !in_array($field_form_type, $translatable_exclude_form_type)) {
        			$add_translatable = true;
        			while($add_translatable == true) {
        				$field_translatable = trim(readline("Is translatable? ([y]/n):"));
                        $field_translatable = ($field_translatable == 'y' || !trim($field_translatable)) ? true : false;
    					$add_translatable = false;
        			}
                }

    			# Length
                if(isset($field_type) && in_array($field_type, $types_with_length) && !in_array($field_form_type, $length_exclude_form_type)) {
        			$add_length = true;
        			while($add_length==true) {
        				$field_length = (int)trim(readline("Field length [$default_length]:"));
        				if(!$field_length) $field_length = $default_length;
        				$add_length = false;
        			}
                }

                # Nullable
                if(!in_array($field_form_type, $types_exclude_form_type)) {
                    $add_nullable = true;
                    while($add_nullable == true) {
                        $field_nullable = trim(readline("Can this field be null in the database (nullable) (y/[n]):"));
                        if(!$field_nullable) $field_nullable = 'y';
                        if($field_nullable == 'y' || $field_nullable == 'n') {
                            $field_nullable = $field_nullable == 'y' ? 'true' : 'false';
                            $add_nullable = false;
                        }
                    }
                }
                


    			# Transformer
    			$add_transformer = true;
                if($field_form_type == 'UIFileType') $default_transformer = 'Uicms\Admin\Form\DataTransformer\FileTransformer';
    			while($add_transformer == true) {
    				$field_transformer = trim(readline("Transformer? [$default_transformer]:"));
    				if(!$field_transformer) $field_transformer = $default_transformer;
                    $add_transformer = false;
                    $form_config['transformer'] = $field_transformer;
                }

                # Class
                $add_attr_class = true;
                while($add_attr_class == true) {
                    $field_attr_class = trim(readline("Class attribute? []:"));
                    $add_attr_class = false;
                    $form_config['options']['attr']['class'] = $field_attr_class;
                }



                # Add in CMS forms config
                if(!isset($ui_config['entity'][$full_entity_name]['form'])) {
                    $ui_config['entity'][$full_entity_name]['form'] = array(
                        'class' =>$full_entity_name,
                        'translations'=>array(),
                        'fields'=>array(),
                    );
                }
                
                # 'Collection type' expects file template and different namespace
                if($field_form_type == 'CollectionType') {
                    $form_config['options']['allow_add'] = true;
                    $form_config['options']['allow_delete'] = true;
                    $form_config['options']['attr']['class'] = 'collectionType';
                }
                
                # 'Entity type' specific options
                if($field_form_type == 'EntityType') {
        			# Target entity class
        			$add_class = true;
        			while($add_class == true) {
                        if($target_entity_class = trim(readline("Target entity class? :"))) {
                            $add_class = false;
                        }
        			}
                    
        			# Choice label
        			$add_choice_label = true;
        			while($add_choice_label == true) {
                        $choice_label = trim(readline("Choice label? [translations[fr].name]:"));
                        if(!$choice_label) $choice_label = 'translations[fr].name';
                        $add_choice_label = false;
        			}
                    
                    $form_config['options']['choice_label'] = $choice_label;
                    $form_config['options']['class'] = $target_entity_class;
                    $form_config['namespace'] = 'Symfony\Bridge\Doctrine\Form\Type';
                }
                
                # 'UI File type' expects file template and different namespace
                if($field_form_type == 'UIFileType') {
                    $form_config['options']['template'] = 'file';
                    $form_config['namespace'] = 'Uicms\Admin\Form\Type';
                }



                # Save config
                $form_section = $field_translatable ? 'translations' : 'fields';
                $ui_config['entity'][$full_entity_name]['form'][$form_section][$field_name] = $form_config;
                
                # Entity file pattern
                $field_pattern = "    /**
                * @ORM\Column(%s)
                */
                private \$" . $field_name .";";
                $attributes = array();
                $attributes[] = "type=\"" . $field_type ."\"";
                if(!in_array($field_form_type, $nullable_exclude_form_type)) {
                    $attributes[] = "nullable=" . $field_nullable;
                }
                if(in_array($field_form_type, $types_with_length)) {
                    $attributes[] = "length=" . $field_length;
                }
                $field_pattern = sprintf($field_pattern, implode(',', $attributes));
                
                # Different field pattern if EntityType
                if($field_form_type == 'EntityType') {
                    $field_pattern = "/**
                * @ORM\ManyToOne(targetEntity=\"" . $target_entity_class . "\")
                * @ORM\JoinColumn(name=\"" . $field_name . "_id\", referencedColumnName=\"id\")
                */
                private \$" . $field_name . ";";
                }
                
                # Commit
                switch($form_section) {
                    
                    case 'translations':
                        if(isset($ui_config['entity'][$full_entity_name]['form']['fields'][$field_name])) {
                            unset($ui_config['entity'][$full_entity_name]['form']['fields'][$field_name]);
                        }
                        if(fieldExists($field_name, $entity_file_content)) {
                            deleteFieldFromEntityFile($field_name, $entity_file, $translation_file, false);
                        }
                        if($field_mode == 'add' || !fieldExists($field_name, $translation_file_content)) {
                            addFieldToEntityFile($entity_file, $translation_file, true, $field_pattern);
                        } else {
                            changeFieldInEntityFile($field_name, $entity_file, $translation_file, true, $field_pattern);
                        }
                    break;
                    
                    case 'fields':
                        if(isset($ui_config['entity'][$full_entity_name]['form']['translations'][$field_name])) {
                            unset($ui_config['entity'][$full_entity_name]['form']['translations'][$field_name]);
                        }
                        if(fieldExists($field_name, $translation_file_content)) {
                            deleteFieldFromEntityFile($field_name, $entity_file, $translation_file, true);
                        }
                        if($field_mode == 'add' || !fieldExists($field_name, $entity_file_content)) {
                            addFieldToEntityFile($entity_file, $translation_file, false, $field_pattern);
                        } else {
                            changeFieldInEntityFile($field_name, $entity_file, $translation_file, false, $field_pattern);
                        }
                    break;
                }
            }
            
        } else {
            $adding = false;
        }
    }
}
print "\nSaving...\n";


# Save UI config
$config['parameters']['ui_config'] = $ui_config;
yaml_emit_file($config_file, $config);


/* Functions */
function getFieldReplacePattern($field_name, $entity_file_content) 
{
	if(preg_match_all(PREG_FIELD_PATTERN, $entity_file_content, $preg)) {
       	foreach($preg[5] as $i=>$preg_field_name) {
       	    if($preg_field_name == $field_name) {
       	       return $preg[0][$i];
       	    }
       	}
    }
    return null;
}

function fieldExists($field_name, $entity_file_content) 
{
	if(preg_match_all(PREG_FIELD_PATTERN, $entity_file_content, $preg)) {
       	foreach($preg[5] as $i=>$preg_field_name) {
       	    if($preg_field_name == $field_name) {
       	       return true;
       	    }
       	}
    }
    return false;
}

function addFieldToEntityFile($entity_file, $translation_file, $is_translatable, $string)
{
	$entity_file_content = file_get_contents($entity_file);
    $translation_file_content = file_exists($translation_file) ? file_get_contents($translation_file) : '';
    
	# Add Entity file property
    $file_content = $is_translatable ? $translation_file_content : $entity_file_content;
    if(preg_match_all(PREG_FIELD_PATTERN, $file_content, $matches)) {
        $match = end($matches[0]);
        $insert_position = strpos($file_content, $match) + strlen($match);
        $file_content = substr_replace($file_content, "\n\n$string\n\n", $insert_position, 0);
    
        # Save in file
        $save_file = $is_translatable ? $translation_file : $entity_file;
        file_put_contents($save_file, $file_content);
    }
}

function changeFieldInEntityFile($field_name, $entity_file, $translation_file, $is_translatable, $string)
{
	$entity_file_content = file_get_contents($entity_file);
    $translation_file_content = file_exists($translation_file) ? file_get_contents($translation_file) : '';
    $file_content = $is_translatable ? $translation_file_content : $entity_file_content;
    
	# Change Entity file property
    $pattern = getFieldReplacePattern($field_name, $file_content);
    $file_content = str_replace($pattern, $string, $file_content);

    # Save in file
    $save_file = $is_translatable ? $translation_file : $entity_file;
    file_put_contents($save_file, $file_content);
}

function deleteFieldFromEntityFile($field_name, $entity_file, $translation_file, $is_translatable)
{
	$entity_file_content = file_get_contents($entity_file);
    $translation_file_content = file_exists($translation_file) ? file_get_contents($translation_file) : '';
    $file_content = $is_translatable ? $translation_file_content : $entity_file_content;
    
	# Add Entity file property
    $pattern = getFieldReplacePattern($field_name, $file_content);
    $file_content = str_replace($pattern, '', $file_content);

    # Save in file
    $save_file = $is_translatable ? $translation_file : $entity_file;
    file_put_contents($save_file, $file_content);
}

function createEntityFiles($table_name, $entity_name)
{
	#
	# Creates files
	#
	$is_table_translatable = readline("Is $table_name translatable? ([y]/n) : ");
	$entity_file_content = file_get_contents('vendor/uicms/app/install/db/entity/Entity.tpl');
	$entity_file_content = str_replace('MyEntity', $entity_name, $entity_file_content);
	$translation_path = "src/Entity/" . $entity_name . "Translation.php";

	if(strtolower(trim($is_table_translatable)) == 'y' || !trim($is_table_translatable)) {
	    $translation_content = file_get_contents('vendor/uicms/app/install/db/entity/EntityTranslation.tpl');
	    $translation_content = str_replace('MyEntity', $entity_name, $translation_content);
	    file_put_contents($translation_path, $translation_content);
	} else {
	    if(file_exists($translation_path)) {
	        unlink($translation_path);
	    }
	    $entity_file_content = str_replace('implements TranslatableInterface', '', $entity_file_content);
	    $entity_file_content = str_replace('use TranslatableTrait;', '', $entity_file_content);
	}
	file_put_contents("src/Entity/$entity_name.php", $entity_file_content);

	$repository_path = "src/Repository/" . $entity_name . "Repository.php";
	if(!file_exists($repository_path)) {
		$repository_content = file_get_contents('vendor/uicms/app/install/db/entity/EntityRepository.tpl');
		$repository_content = str_replace('MyEntity', $entity_name, $repository_content);
		file_put_contents($repository_path, $repository_content);
	}
}

function inArray($needle, $array)
{
	foreach($array as $key=>$values) {
		foreach($values as $value) {
			if($needle == $value) {
				return true;
			}
		}
	}
	return false;
}



