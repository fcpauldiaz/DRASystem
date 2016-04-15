<?php

namespace CostoBundle\Controller;

class QueryController
{
   /**
     * Agregar elemento a un array collection.
     *
     * @param ArrayCollection $array1
     * @param T               $item
     */
    public function addArrayCollectionAction($array1, $item)
    {
        if (!$array1->contains($item)) {
            $array1->add($item);
        }

        return $array1;
    }

     /**
     * Unir dos ArrayCollection.
     *
     * @param ArrayCollection $array1
     * @param ArrayCollection $array2
     *
     * @return ArrayCollection
     */
    public function mergeArrayCollectionAction($array1, $array2)
    {
        foreach ($array2 as $item) {
            if (!$array1->contains($item)) {
                $array1->add($item);
            }
        }
        dump($array1);
        return $array1;
    }
}