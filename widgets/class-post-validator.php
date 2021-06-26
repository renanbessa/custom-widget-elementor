<?php

class Post_Validator{

    private $data;
    private $errors = [];
    private static $fields = ['postid'];

    public function __construct($post_data){
        $this->data = $post_data;
    }

    public function validateForm(){
        foreach(self::$fields as $field){
            if(!array_key_exists($field, $this->data)){
                trigger_error("$field is not present in data");
                return;
            }
        }
        $this->validatePostID();
        return $this->errors;
    }

    private function validatePostID(){
        $val = trim($this->data['postid']);

        if(empty($val)){
            $this->addError('postid', 'Post ID cannot be empty');
        } else {
            if(!filter_var($val, FILTER_VALIDATE_INT)) {
                $this->addError('postid', 'Post ID must bem a valid ID');
            }
        }
    }

    private function addError($key, $val){
        $this->errors[$key] = $val;
    }
}