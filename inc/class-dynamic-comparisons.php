<?php
/**
 * Dynamic Comparisons
 * @author Fabio Gonzaga, adapted from Tarek Adam
 * @package https://gist.github.com/flgonzaga/e347682f2f95a7ac0c39552a29d5554b
 * @example 
 * class FooBar
 * {
 *  
 *     use DynamicComparisons;
 *     
 *     public function test()
 *     {
 *         $this->verbose_mode = true;
 * 
 *         $value_a    = 1;
 *         $value_b    = 3;
 *         $operation  = 'lessThan';
 *         $result = $this->is($value_a, $operation, $value_b);
 * 
 *         return $result; //boolean
 *     }
 * 
 * }
 * 
 */
trait DynamicComparisons
{
    protected $verbose_mode = false;

    /**
    * Avaliable operations methods
    */
    private $operatorToMethodTranslation = [
        '=='  => 'equal',
        '===' => 'totallyEqual',
        '!='  => 'notEqual',
        '>'   => 'greaterThan',
        '<'   => 'lessThan'
    ];

    /**
    * Comparation method
    */
    protected function is($value_a, $operation, $value_b)
    {
        try {
            if ($this->verbose_mode == true)
            {
                if (method_exists($this, $operation))
                {
                    return $this->$operation($value_a, $value_b);
                }
                else 
                {
                    throw new \Exception('Unknown Method.');
                }
            } 
            else if($method = $this->operatorToMethodTranslation[$operation])
            {
                return $this->$method($value_a, $value_b);
            }
            throw new \Exception('Unknown Dynamic Operator.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function equal($value_a, $value_b)
    {
        return $value_a == $value_b;
    }

    private function totallyEqual($value_a, $value_b)
    {
        return $value_a === $value_b;
    }

    private function notEqual($value_a, $value_b)
    {
        return $value_a != $value_b;
    }

    private function greaterThan($value_a, $value_b)
    {
        return $value_a > $value_b;
    }

    private function lessThan($value_a, $value_b)
    {
        return $value_a < $value_b;
    }

    private function greaterThanOrEqual($value_a, $value_b)
    {
        return $value_a >= $value_b;
    }

    private function lessThanOrEqual($value_a, $value_b)
    {
        return $value_a <= $value_b;
    }

}
