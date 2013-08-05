<?php

namespace Manhattan\Bundle\PostsBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Manhattan\Bundle\PostsBundle\Entity\Category;
use Manhattan\Bundle\PostsBundle\Entity\Post;
use Manhattan\Bundle\PostsBundle\Entity\Image;

class FixtureLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category_one = new Category();
        $category_one->setTitle('Category One');
        $manager->persist($category_one);

        for ( $i = 0; $i < 10; ++$i )
        {
            $post = new Post();
            $post->setTitle('Post Title '. $i);
            $post->addCategory($category_one);
            $post->setExcerpt('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed congue pulvinar egestas. Aliquam erat volutpat. Praesent tempor tincidunt elit sit amet lobortis. Duis cursus massa sed libero adipiscing lacinia. Praesent mauris arcu, convallis at pulvinar at, tempus a lacus.');
            $post->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed congue pulvinar egestas. Aliquam erat volutpat. Praesent tempor tincidunt elit sit amet lobortis. Duis cursus massa sed libero adipiscing lacinia. Praesent mauris arcu, convallis at pulvinar at, tempus a lacus. Donec massa magna, aliquet sit amet fermentum et, ultrices in velit. Ut semper elementum eros ac faucibus. Nulla facilisi. Phasellus non risus neque. Mauris sollicitudin, tellus sit amet euismod gravida, magna lectus interdum magna, vitae malesuada diam ipsum non ante.' .
                'In facilisis congue ante, ac malesuada dui feugiat at. Integer et velit ligula, in porttitor lacus. In tempor lacinia ipsum vel fermentum. Suspendisse laoreet justo at enim fermentum posuere. Duis convallis pharetra elementum. Pellentesque purus lorem, dictum non viverra et, fringilla elementum nunc. Praesent pellentesque lacus at eros tristique interdum. Etiam et ultricies justo. Vestibulum adipiscing magna sit amet mi consectetur vel placerat neque placerat. Donec scelerisque odio vitae nisi vulputate nec tincidunt augue tempus. Aliquam auctor cursus orci, ut tincidunt nibh lobortis id. Vestibulum sit amet purus quis velit rutrum faucibus. Nullam elementum mauris et nisl feugiat non dignissim mauris vulputate. Praesent a magna luctus urna tempus tincidunt.'.
                'Cras placerat convallis ante, sit amet placerat dui pellentesque at. Nulla elit dui, fermentum ac sodales vitae, consequat non nibh. Vestibulum cursus placerat lorem ac vulputate. Ut aliquet diam ut lectus dictum ullamcorper. Sed eget lacus ante. Vestibulum a ligula at leo pharetra adipiscing. In sapien leo, blandit vitae vehicula a, blandit et massa. Sed in nulla metus, vitae egestas quam. Nullam viverra vehicula interdum. Nullam porttitor nisl at diam laoreet id porta eros faucibus.' .
                'Praesent nec mauris sapien, id ullamcorper arcu. Aenean a magna ligula, vitae adipiscing dui. Sed sollicitudin porttitor convallis. Morbi imperdiet leo et lectus consequat tempus. Fusce mollis, sapien a tempus rhoncus, arcu augue laoreet libero, a varius neque neque vel risus. Fusce venenatis gravida leo sit amet luctus. Mauris ac nulla sit amet nisl tempor rhoncus. Fusce imperdiet, nunc non elementum tristique, purus ante laoreet nisl, ac pharetra lorem nulla vitae orci. Curabitur ultricies nisl nec tellus semper congue. Aliquam risus massa, adipiscing eget vulputate eu, tempus non purus.'
            );
            //$post->setPublishDate(new \DateTime());
            $post->setPublishState(($i % 2) ? 2 : 1);

            $manager->persist($post);
        }

        $manager->flush();
    }

}
