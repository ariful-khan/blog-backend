<?php

namespace BlogBundle\DataFixtures\ORM;

use BlogBundle\DataFixtures\FixturesTrait;
use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class PostFixtures extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
    use FixturesTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getRandomPostTitles() as $i => $title) {
            $post = new Post();

            $post->setTitle($title);
            $post->setSummary($this->getRandomPostSummary());
            $post->setSlug($this->container->get('slugger')->slugify($post->getTitle()));
            $post->setContent($this->getPostContent());
            // "References" are the way to share objects between fixtures defined
            // in different files. This reference has been added in the UserFixtures
            // file and it contains an instance of the User entity.
            $post->setAuthor($this->getReference('jane-admin'));
            $post->setPublishedAt(new \DateTime('now - '.$i.'days'));

            // for aesthetic reasons, the first blog post always has 2 tags
            foreach ($this->getRandomTags($i > 0 ? mt_rand(0, 3) : 2) as $tag) {
                $post->addTag($tag);
            }

            foreach (range(1, 5) as $j) {
                $comment = new Comment();

                $comment->setAuthor($this->getReference('john-user'));
                $comment->setPublishedAt(new \DateTime('now + '.($i + $j).'seconds'));
                $comment->setContent($this->getRandomCommentContent());
                $comment->setPost($post);

                $manager->persist($comment);
                $post->addComment($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * Instead of defining the exact order in which the fixtures files must be loaded,
     * this method defines which other fixtures this file depends on. Then, Doctrine
     * will figure out the best order to fit all the dependencies.
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            TagFixtures::class,
            UserFixtures::class,
        ];
    }

    private function getRandomTags($numTags = 0)
    {
        $tags = [];

        if (0 === $numTags) {
            return $tags;
        }

        $indexes = (array) array_rand($this->getTagNames(), $numTags);
        foreach ($indexes as $index) {
            $tags[] = $this->getReference('tag-'.$index);
        }

        return $tags;
    }
}
