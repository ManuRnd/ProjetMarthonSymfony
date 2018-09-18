<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 17:17
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class VoteEvent extends Event
{
    protected $vote;

    /**
     * @return mixed
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param mixed $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }

}