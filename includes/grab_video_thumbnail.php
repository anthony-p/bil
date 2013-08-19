<?php
/**
 * Created by Lilian Codreanu.
 * User: Lilian Codreanu
 * Date: 8/19/13
 * Time: 4:57 PM
 */

class GrabVideoThumbnail {
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $image;

    /**
     * @var string
     */
    private $cacheDir = 'images/partner_logos';

    /**
     * @var string
     */
    private $filename = '';

    /**
     * @var string
     */
    private $workDir = '';

    /**
     * @param $url
     * @param $filename
     */
    public function __construct($url,$filename) {
        $this->workDir = __DIR__.'/../'.$this->cacheDir;
        $this->url = $url;
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(){
        return $this->image;
    }

    /**
     * @return string
     */
    public function getCacheDir() {
        return $this->cacheDir;
    }

    public function getThumbnail() {
        if (file_exists($this->workDir.'/'.$this->filename.'.jpg')) {
            $this->image = $this->cacheDir.'/'.$this->filename.'.jpg';
            return true;
        }
        if (strpos($this->url,'youtube.') !== false)
            return $this->getYoutubeImage();
        else
            $this->getVimeImage();

    }

    /**
     * @return bool
     */
    private function getYoutubeImage() {
        $url = $this->url;
        $queryString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $params);
        if (isset($params['v'])) {
            $vidID = $params['v'];
            $thumb = file_get_contents("http://img.youtube.com/vi/$vidID/0.jpg");
            file_put_contents($this->workDir.'/'.$this->filename.'.jpg',$thumb);
            $this->image = $this->cacheDir.'/'.$this->filename.'.jpg';
            return true;
        }
        return false;
//        $thumb2 = file_get_contents("http://img.youtube.com/vi/$vidID/1.jpg");
//        $thumb3 = file_get_contents("http://img.youtube.com/vi/$vidID/2.jpg");
//        $thumb4 = file_get_contents("http://img.youtube.com/vi/$vidID/3.jpg");
    }

    /**
     * @return bool
     */
    private function getVimeImage() {
        $url = $this->url;
        $vidID =  substr(parse_url($url, PHP_URL_PATH), 1);
        $jSONData = file_get_contents("http://vimeo.com/api/v2/video/$vidID.json");
        $data = json_decode($jSONData);
        $data = $data[0];
        if (isset($data->thumbnail_large)) {
            $thumb = file_get_contents($data->thumbnail_large);
            file_put_contents($this->workDir.'/'.$this->filename.'.jpg',$thumb);
            $this->image = $this->cacheDir.'/'.$this->filename.'.jpg';
            return true;
        }
        return false;
    }

}