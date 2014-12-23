<?php

namespace Nutshell\EloquentSearch\Traits;

trait SearchTrait
{
    private $className;
    private $builder = array();

    private function __construct()
    {
        $className = __CLASS__;
        $this->builder = $className::newQuery();
        $this->className = $className;
    }

    public function search()
    {
        return $this->builder->get();
    }

    public function setAllParams()
    {
        $className = $this->className;
        $inputParams = $className::expectedInput("searchParams");
        foreach($inputParams as $key => $params) {

            $function = 'set' . ucfirst($key);
            $this->$function($params);
        }
    }

    private function preProcessVar($var) {
        if(empty($var)) {
            return FALSE;
        }
        if(!is_array($var)) {
            return array($var);
        }
        return $var;
    }

    private function where($var, $columnName)
    {
        if($var != null || is_bool($var) || is_numeric($var)) {
            $this->builder->where($columnName, $var);
        }
        return $this;
    }

    private function whereIn($var, $columnName)
    {
        $var = $this->preProcessVar($var);
        if($var) {
            $this->builder->whereIn($columnName, $var);
        }
        return $this;
    }

    private function whereHasIn($var, $relationName, $tableName)
    {
        $var = $this->preProcessVar($var);
        if($var) {
            $this->builder->whereHas($relationName, function($q) use($var, $tableName)
            {
                $q->whereIn($tableName . '.id', $var);
            });
        }
        return $this;
    }
}