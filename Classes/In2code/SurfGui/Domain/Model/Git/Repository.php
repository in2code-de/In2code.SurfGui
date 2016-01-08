<?php
namespace In2code\SurfGui\Domain\Model\Git;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * class Repository
 *
 * @Flow\Entity
 */
class Repository
{
    /**
     * @var string
     * @Flow\Identity
     */
    protected $url;

    /**
     * @var \Doctrine\Common\Collections\Collection<\In2code\SurfGui\Domain\Model\Git\Branch>
     * @ORM\ManyToMany(cascade={"persist"})
     */
    protected $branches;

    /**
     * @var \Doctrine\Common\Collections\Collection<\In2code\SurfGui\Domain\Model\Git\Tag>
     * @ORM\ManyToMany(cascade={"persist"})
     */
    protected $tags;

    /**
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->branches = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Branch $branch
     * @return $this
     */
    public function addBranch(Branch $branch)
    {
        $this->branches->add($branch);
        return $this;
    }

    /**
     * @param Branch $branch
     * @return $this
     */
    public function removeBranch(Branch $branch)
    {
        $this->branches->removeElement($branch);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * @param Collection $branches
     * @return $this
     */
    public function setBranches(Collection $branches)
    {
        $this->branches = $branches;
        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     * @return $this
     */
    public function setTags(Collection $tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = new ArrayCollection();
        foreach ($this->tags as $tag) {
            $options->add($tag);
        }
        foreach ($this->branches as $branch) {
            $options->add($branch);
        }
        return $options;
    }
}
