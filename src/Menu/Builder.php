<?php
/**
 * Created by PhpStorm.
 * User: Egor
 * Date: 26.11.2018
 * Time: 10:43
 */

namespace App\Menu;
use Knp\Menu\FactoryInterface ;
use Symfony\Component\DependencyInjection\ContainerAwareInterface ;
use Symfony\Component\DependencyInjection\ContainerAwareTrait ;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Builder implements ContainerAwareInterface
{

    use ContainerAwareTrait ;
    /**
     * Sets the container.
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('All posts',['route' => '/']);
        $menu->addChild('Popular',['route' => 'popular']);
        return $menu;
    }


}