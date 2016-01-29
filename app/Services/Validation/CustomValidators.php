<?php
namespace Ghi\Services\Validation;

use Illuminate\Support\Arr;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomValidators
 *
 * @author EMartinez
 */

class CustomValidators  {

	
	public function requiredIfNot( $attribute, $value, $parameters, $validator) {
            //dd($attribute, $value, $parameters, $validator->getData());
            //C:\httpd\www\php56.loc\www.mg.mx\vendor\laravel\framework\src\Illuminate\Validation\Validator.php 680
            $this->requireParameterCount(2, $parameters, 'required_if_not');
            //se obtiene el valor que trae del formulario del campo que se mando en parametro, p.e. unidad_inventariar
            $data = Arr::get($validator->getData(), $parameters[0]);
            //arreglo donde viene el valor/valores que no debe tener el campo mandado como parametro 0
            $values = array_slice($parameters, 1);
            //dd($attribute, $value, $parameters, $data, $values);
            if (!in_array($data, $values)) {
                return $this->validateRequired($attribute, $value);
            }
            return true;
	}
        public function alMenosUnoIngresado($attribute, $value, $parameters, $validator){
            $datos = $validator->getData();
            $cantidades = $this->getValoresConLlave($datos["existencia"], $parameters[0]);
            //dd($datos["existencia"],$parameters[0],$cantidades);
            if(count($cantidades)>0){
                return true;
            }else{
                return false;
            }
        }
        public function rfc( $attribute, $value, $parameters, $validator) {
            
             if(strlen(str_replace(" ","", $value))>0){
               $reg_exp = '/[A-Z]{3,4}[\-]?[0-9]{2}((0{1}[1-9]{1})|(1{1}[0-2]{1}))((0{1}[1-9]{1})|([1-2]{1}[0-9]{1})|(3{1}[0-1]{1}))[\-]?[A-Z|0-9]{3}/';
               if (!preg_match($reg_exp, $value)) {
                  return false;
               }else{
                   return true;
               }
           }else{
               return true;
           }
	}
        protected function getValoresConLlave($arreglo, $llave){
            $nuevo_arreglo = [];
            foreach ($arreglo as $partida){
                foreach ($partida as $k=>$v){
                    if($k === $llave && $v!=""){
                        $nuevo_arreglo[] = $v;
                    }
                }
            }
            
            return $nuevo_arreglo;
        }
    /**
     * Validate that a required attribute exists.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateRequired($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        } elseif ($value instanceof File) {
            return (string) $value->getPath() != '';
        }

        return true;
    }

    protected function requireParameterCount($count, $parameters, $rule)
    {
        if (count($parameters) < $count) {
            throw new InvalidArgumentException("Validation rule $rule requires at least $count parameters.");
        }
    }
	

}
