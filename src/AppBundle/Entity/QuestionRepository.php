<?php

/**
 * @author Damian Dlugosz <bigfootdd@gmail.com>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    /**
     * @param string|string[] $keywords
     * @return Question[]
     */
    public function search($keywords)
    {
        $keywords = explode(' ', preg_replace('/[\W_]+/', ' ', $keywords));

        $qb = $this->createQueryBuilder('q');

        foreach ($keywords as $i => $keyword) {
            if (strlen($keyword = trim($keyword))) {
                $orx = $qb->expr()->orX();
                $orx->add('q.title LIKE :keyword'.$i);
                $orx->add('q.content LIKE :keyword'.$i);

                $qb->andWhere($orx)
                    ->setParameter('keyword'.$i, '%'.$keyword.'%');
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Question[]
     */
    public function mostSearched()
    {
        return $this->findBy(array(), array('searchesCount' => 'DESC'));
    }

    /**
     * @return Question[]
     */
    public function mostViewed()
    {
        return $this->findBy(array(), array('viewsCount' => 'DESC'));
    }

    /**
     * @return Question[]
     */
    public function newest()
    {
        return $this->findBy(array(), array('createdAt' => 'DESC'));
    }
}
