<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 09:55
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{

    protected $article;

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }


}