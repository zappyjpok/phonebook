<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 6/09/2015
 * Time: 1:01 PM
 */



class Links {

    /**
     * In my testing environment I noticed that sometimes links would not work unless I used the project
     * name in the url.  To avoid writing out full links, that will become a problem when the site goes to
     * production.  I have decided to write this function that will check if this is the local environment
     * or production environment.  If its local it will include the project name in the url or it will ignore
     * it.
     *
     * @param $name
     * @return mixed|string
     */
    public static function action_link($name)
    {
        $name = ($name[0] === '/')  ? '/' . $name  :    $name;
        $url = filter_var(rtrim($name, '/'), FILTER_SANITIZE_URL);
        if($_SERVER['SERVER_NAME'] === 'localhost')
        {
            $url = '/shoppingcart/Public/' . $name;
        } else {
            $url = 'http://a78135893.tafenowweb.net/' . $name;
        }
        return $url;
    }

    /**
     * Change the image path to include a _tn to get the thumbnail rather than the full image
     *
     * @param $image
     * @return mixed
     */
    public static function changeToThumbnail($image)
    {
        $nameParts = pathinfo($image);
        $Name = $nameParts['filename'];
        $pattern = "/$Name/";
        $replace = $Name. '_tn';
        $newName = preg_replace($pattern, $replace, $image);

        return $newName;
    }
}