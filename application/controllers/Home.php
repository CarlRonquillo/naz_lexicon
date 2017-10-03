<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$this->load->model('DictionaryModel');
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['records'] = $this->DictionaryModel->baseform_get_term(0,0);
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$this->load->view('dictionary',$data);
	}

	public function Terms()
	{
		$this->load->model('DictionaryModel');
		$searched_item = $this->input->get('search');
		if(isset($searched_item))
		{
			$data['terms'] = $this->DictionaryModel->search_terms($searched_item);
		}
		else
		{
			$data['terms'] = $this->DictionaryModel->get_terms();
		}
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$data['searched'] = $searched_item;
		$this->load->view('view_terms',$data);
	}

	public function BaseForm()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$inflectionID = $this->input->get('inflection');
		$data['Inflections'] = $this->DictionaryModel->get_inflections($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['PartOfSpeech'] = $this->DictionaryModel->get_partOfSpeech();
		//$data['Baseform'] = $this->DictionaryModel->get_BaseForm($baseID);
		$data['record'] = $this->DictionaryModel->get_Singleinflection($inflectionID);
		//$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$this->load->view('add_baseform',$data);
	}

	public function Term()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$termID = $this->input->get('term');
		
		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['baseID'] = $baseID;
		$data['first_TermID'] =  $this->DictionaryModel->get_first_termID($baseID);
		$data['record'] =  $this->DictionaryModel->get_SingleTerm($termID);
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$related_terms = $this->DictionaryModel->get_relatedTerms($termID);
		$data['relatedTerms'] = "";
		if(count($related_terms))
		{
			foreach($related_terms as $related)
			{
				$data['relatedTerms'] = $data['relatedTerms'].$related->TermName.',';
			}
		}

		$this->load->view('add_term',$data);
	}

	public function Translation()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$translationID = $this->input->get('translation');

		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['Translations'] = $this->DictionaryModel->get_translations($this->input->get('term'));
		$data['first_TermID'] =  $this->DictionaryModel->get_first_termID($baseID);
		$data['record'] =  $this->DictionaryModel->get_Singletranslations($translationID);
		//$data['baseID'] = $baseID;
		$this->load->view('add_translation',$data);
	}

	public function save_BaseName($baseID)
	{
		$this->form_validation->set_rules('BaseName','Base Form','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$data = $this->input->post();
		$isUpdate = $data['saveBaseName'];
		if ($this->form_validation->run())
        {
        	if($isUpdate)
        	{
        		if($this->DictionaryModel->update_basename($data,$baseID))
	            {
	            	$this->session->set_flashdata('response',$data['BaseName'].' successfully updated.');
	            }
	            else
	            {
					$this->session->set_flashdata('response',$data['BaseName'].' was not updated.');
	            }
	            $baseForm = $baseID;
        	}
        	else
        	{
        		unset($data['saveBaseName']);
				if($this->DictionaryModel->saveRecord($data,'baseform'))
	            {
	            	$this->session->set_flashdata('response',$data['BaseName'].' successfully saved.');
	            }
	            else
	            {
					$this->session->set_flashdata('response',$data['BaseName'].' was not saved.');
	            }
	            $baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        	}
        }

        return redirect("home/BaseForm?baseForm={$baseForm}&inflection=0");
        //$this->load->view('wizard_test',$data);
	}

	public function save_Inflection($baseID,$inflectionID)
	{
		$this->form_validation->set_rules('InflectionName','Inflection','required|is_unique[inflection.InflectionName]');
		$this->form_validation->set_rules('PartOfSpeech','Part of Speech','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$inflectionName = $this->input->post('InflectionName');
		$partOfSpeech = $this->input->post('PartOfSpeech');

		if ($this->form_validation->run())
        {
        	if(isset($inflectionID) && $inflectionID == 0)
			{
				if($this->DictionaryModel->save_inflection($inflectionName,$baseID,$partOfSpeech))
	            {
	            	$this->session->set_flashdata('response','Inflection successfully saved.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Inflection was not saved.');
	            }
			}
			else
			{
				if($this->DictionaryModel->update_inflection($inflectionName,$inflectionID,$partOfSpeech))
	            {
	            	$this->session->set_flashdata('response','Inflection successfully updated.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Inflection was not updated.');
	            }
			}
        	
        }
        
        //$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/BaseForm?baseForm={$baseID}&inflection={$inflectionID}");
        //$this->load->view('wizard_test',$data);
	}

	public function save_Term($baseID,$termID)
	{
		$this->form_validation->set_rules('TermName','Term Name','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$data = $this->input->post();

		$tags = $this->input->post('RelatedTerms');
		$DeletedTags = $this->input->post('DeletedTerms');
		
			if ($this->form_validation->run())
	        {
	        	if(isset($termID) && $termID == 0)
				{
					if($this->DictionaryModel->save_term($data,$baseID))
		            {
		            	$this->session->set_flashdata('response','Term successfully saved.');
		            }
		            else
		            {
						$this->session->set_flashdata('response','Term was not saved.');
		            }
				}
				else
				{
					if($this->DictionaryModel->update_term($data,$termID))
					{
		            	$this->session->set_flashdata('response','Term successfully updated.');
		            }
		            else
		            {
						$this->session->set_flashdata('response','Term was not updated.');
		            }
				}

				if(isset($DeletedTags))
				{
					$this->DictionaryModel->delete_RelatedTerms($DeletedTags,$termID);
				}

				if(isset($tags))
				{
					$this->DictionaryModel->manage_RelatedTerms($tags,$termID);
				}
	        }
        
        //$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/Term?baseForm={$baseID}&term={$termID}");
        //$this->load->view('wizard_test',$data);
	}

	public function save_Translation($baseID,$termID,$translationID)
	{
		$this->form_validation->set_rules('Translation','Translation','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');

		$data = $this->input->post();
		if ($this->form_validation->run())
        {
        	if(isset($translationID) && $translationID == 0)
			{
				if($this->DictionaryModel->saveRecord($data,'translation'))
	            {
	            	$this->session->set_flashdata('response','Translation successfully saved.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Translation was not saved.');
	            }
			}
			else
			{
				if($this->DictionaryModel->update_translation($data,$translationID))
	            {
	            	$this->session->set_flashdata('response','Translation successfully updated.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Translation was not updated.');
	            }
			}
        }
        
        //$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/Translation?baseForm={$baseID}&term={$termID}&translation={$translationID}");
        //$this->load->view('wizard_test',$data);
	}

	public function search()
	{
		$this->load->model('DictionaryModel');
		$searched_item = $this->input->get('search');
		$language_id = $this->input->get('Language');

		$data['baseName'] = $this->DictionaryModel->search_get_baseform($searched_item,$language_id);
		$baseForm = $data['baseName'];
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['records'] = $this->DictionaryModel->baseform_get_term($baseForm['BaseFormID'],$language_id);
		$data['records_more'] = $this->DictionaryModel->baseform_get_term_more($baseForm['BaseFormID'],$language_id);
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$data['related_words_ID'] = $this->DictionaryModel->related_words_ID($baseForm['BaseFormID'],$language_id);

		$this->load->view('dictionary',$data);
	}

	public function get_BaseID()
	{
		$this->load->model('DictionaryModel');
		$baseform = $this->input->post('base');
		$baseID = $this->DictionaryModel->get_BaseID($baseform);

		if(!isset($baseID))
		{
			$baseID=0;
		}
		else
		{
			$BaseName = $this->DictionaryModel->get_BaseForm($baseID);
		}

		return redirect("home/BaseForm?baseForm={$baseID}&inflection=0&baseName={$BaseName}");
	}

	public function delete_term($record_id,$tableName,$fieldName,$isViewTerm,$baseID)
	{
		$data = array('Deleted' => 1, 'DateDeleted' => date("Y-m-d H:i:s"));
		$this->load->model('DictionaryModel');

		if($this->DictionaryModel->deleteRecord($record_id,$data,$tableName,$fieldName))
		{
			$this->session->set_flashdata('response','Record Successfully Deleted.');
		}
		else
		{
			$this->session->set_flashdata('response','Record Not Deleted!');
		}

		if($isViewTerm)
		{
			$redirect = "home/Terms";
		}
		else
		{
			$redirect = "home/Term?baseForm={$baseID}&term=0";
		}
		return redirect($redirect);
	}

	public function delete_translation($record_id)
	{
		$data = array('Deleted' => 1, 'DateDeleted' => date("Y-m-d H:i:s"));
		$this->load->model('DictionaryModel');

		if($this->DictionaryModel->deleteRecord($record_id,$data,'translation','TranslationID'))
		{
			$this->session->set_flashdata('response','Record Successfully Deleted.');
		}
		else
		{
			$this->session->set_flashdata('response','Record Not Deleted!');
		}
		return redirect("home/Translation?baseForm=1&term=1");
	}

	public function delete_baseform($record_id,$tableName,$fieldName)
	{
		$data = array('Deleted' => 1, 'DateDeleted' => date("Y-m-d H:i:s"));
		$this->load->model('DictionaryModel');

		if($this->DictionaryModel->deleteRecord($record_id,$data,$tableName,$fieldName))
		{
			$this->session->set_flashdata('response','Record Successfully Deleted.');
		}
		else
		{
			$this->session->set_flashdata('response','Record Not Deleted!');
		}

		$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/BaseForm?baseForm={$baseForm}&inflection=0");
	}

	public function Edit_Term($termID)
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['baseID'] = $baseID;
		$data['first_TermID'] =  $this->DictionaryModel->get_first_termID($baseID);
		$this->load->view('edit_term',$data);
	}

}
