<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 21/12/17
 * Time: 01:39
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class TrainingEvent extends Event
{
    protected $training;

    /**
     * @return mixed
     */
    public function getTraining()
    {
        return $this->training;
    }

    /**
     * @param mixed $training
     */
    public function setTraining($training)
    {
        $this->training = $training;
    }


}