<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Welcome_m extends CI_Model {
 
 public function get_featured($cat, $featured){
 	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp, categories.*");
	$this->db->from("news");	
	$this->db->join("categories", "categories.cat_id=news.category");
	
	$this->db->where("news.featured", $featured);
	$this->db->where("news.section", $cat);
	
	
	$result = $this->db->get();
	$news = $result->result_array();
	return $news;
 }
 public function get_all_featured(){
	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp, categories.*");
	$this->db->from("news");	
	$this->db->join("categories", "categories.cat_id=news.category");

	$this->db->where("news.featured", 1);
	
	$result = $this->db->get();
	$news = $result->result_array();
	return $news;
 }
  public function get_all(){
 	/*
	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp, categories.*");
	$this->db->from("news");	
	$this->db->join("categories", "categories.cat_id=news.category");
	$result = $this->db->get();
	$news = $result->result_array();
	return $news;
	*/
	$feed_url = $this->config->item('feed_url');
	$feed = simplexml_load_file($feed_url);
	$news = array();
	if($feed !=null){
		$items = (array)$feed->channel;
		$items = $items['item'];
	

		foreach($items as $item){
			$newitem = array();
			$newitem['title'] = $item->title;		
			$newitem['description'] = $this->first_paragraph($item->description);
			$newitem['link'] = $item->link;
			$newitem['timestamp'] = $item->pubDate;

			$namespaces = $item->getNameSpaces(true);
			$dc = $item->children($namespaces['dc']); 
			$newitem['author'] =$dc->creator;
		
			$news[] = $newitem;
		}
	}
	return $news;
 }
	public function first_paragraph($string){
		$string = explode("</p>", $string);
		if(sizeof($string)>0){
			$newstring = $string[0]."</p>";	
		}else{
			//return only first two sentences
			//first split at ... to retain social media links
			$string = explode("...", $string);
			//now split the first bit using fullstops
			$string2 = explode(". ", $string[0]);
			//then combine parts
			$newstring = $string2[0].'. '.$string2[1].'...<br />'.$string[1];
		}
		
		return $newstring;
}
   public function get_archive($cat){
 	
	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp, categories.*");
	$this->db->from("news");	
	$this->db->join("categories", "categories.cat_id=news.category");
	$this->db->where("category", $cat);
	
	$result = $this->db->get();
	$news = $result->result_array();
	return $news;
 }
    public function show_article($id){
 	
	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp, categories.*");
	$this->db->from("news");	
	$this->db->join("categories", "categories.cat_id=news.category");
	$this->db->where("id", $id);
	
	$result = $this->db->get();
	$news = $result->result_array();
	return $news;
 }
   public function get_facilities_counties(){
   	$this->db->select("*");
   	$this->db->from("counties");
	
	$result = $this->db->get();
	return $result->result_array();
   }
   public function get_towns(){
   	$this->db->select("*");
	$this->db->from("nhif");
	$this->db->where("Town !=", "");
	$this->db->group_by("Town");
	$result = $this->db->get();
	return $result->result_array();
   }
   public function get_story_sofar($parent){
   	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp");
	$this->db->from("news");

	$this->db->where("parent", $parent);
	
	
	$result = $this->db->get();
	return $result->result_array();

   }
   public function get_helplines($story){
	/*$this->db->select("h_story.*, helplines.*");
	$this->db->from("h_story");
	$this->db->join("helplines", "h_story.helpline_id=helplines.h_id");
	$this->db->where("story_id", $story);
	*/
	$result = $this->db->get('helplines');
	return $result->result_array();
   }
   public function get_supportgroups($story){
	/*$this->db->select("sg_story.*, supportgroups.*");
	$this->db->from("sg_story");
	$this->db->join("supportgroups", "sg_story.sg_id=supportgroups.sg_id");
	$this->db->where("story_id", $story);
	*/
	$result = $this->db->get('supportgroup');
	return $result->result_array();
   }
   public function get_socialmedias($story){
	/*$this->db->select("sm_story.*, socialmedia.*");
	$this->db->from("sm_story");
	$this->db->join("socialmedia", "sm_story.sm_id=socialmedia.sm_id");
	$this->db->where("story_id", $story);
	*/
	$result = $this->db->get('socialmedia');
	return $result->result_array();
   }
   public function get_filtered_feed($section){
   	$this->db->select("*, UNIX_TIMESTAMP() - timestamp AS TimeSpent, timestamp, categories.*");
	$this->db->from("news");	
	$this->db->join("categories", "categories.cat_id=news.category");	
	if($section=="0"){
		
	}else{
	$this->db->where("news.section", $section);	
	}
	$result = $this->db->get();
	return $result->result_array();
   }
}
