<?php 
	class DictionaryModel extends CI_Model
	{
		public function get_BaseForm($BaseformID)
		{
			$query = $this->db->get_where('baseform', array('BaseFormID' => $BaseformID));
			return $query->row()->BaseName;
		}

		public function get_BaseID($Baseform)
		{
			$query = $this->db->get_where('baseform', array('BaseName' => $Baseform, 'Deleted' => 0));
			return $query->row()->BaseFormID;
		}

		public function get_TermNames()
		{
			$this->db->distinct();
			$this->db->select('TermID,TermName');
			$this->db->from('term');
			$this->db->where('Deleted',0);
			$query = $this->db->get();
			return $query->result_array();
		}

		public function get_relatedTerms($termID)
		{
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('termrelatestoterm', 'termrelatestoterm.FKTermIDChild = term.TermID');
			$this->db->order_by('TermName', 'ASC');
			$this->db->where('term.Deleted',0);
			$this->db->where('termrelatestoterm.FKTermIDParent',$termID);
			$query = $this->db->get();
			return $query->result();
		}

		public function get_BaseNames()
		{
			$this->db->distinct();
			$this->db->select('BaseName,BaseFormID');
			$this->db->from('baseform');
			$this->db->order_by('BaseName', 'ASC');
			$this->db->where('Deleted',0);
			$query = $this->db->get();
			return $query->result();
		}

		public function get_specific_account($user_id)
		{
			$this->db->select('ID,FirstName,LastName,Username,Password');
			$this->db->from('accounts');
			$this->db->where('ID',$user_id);
			$this->db->where('Deleted',0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row_array();
			}
		}

		public function get_languages()
		{
			$this->db->distinct();
			$this->db->select('*');
			$this->db->order_by('Language', 'ASC');
			$query = $this->db->get('languages');
			return $query->result();
		}

		/*public function search_get_baseform($Searched_term,$languageID)
		{
			$this->db->select('baseform.BaseName,baseform.BaseFormID');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKTermID = term.TermID');
			$this->db->join('baseform', 'baseform.BaseFormID = termhasbaseform.FKBaseValueID');
			$this->db->join('basehasinflection', 'basehasinflection.FKBaseValueID = baseform.BaseFormID','left');
			$this->db->join('inflection', 'inflection.InflectionID= basehasinflection.FKInflectionID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('term.TermName',$Searched_term);
			$this->db->or_where('inflection.InflectionName',$Searched_term);
			$this->db->or_where('baseform.BaseName',$Searched_term);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row_array();
			}
		}*/

		public function search_existing_term($Searched_term)
		{
			$this->db->select('term.TermID,term.TermName,term.GlossaryEntry');
			$this->db->from('term');
			$this->db->like('term.TermName',$Searched_term);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function termExists($Searched_term,$languageID)
		{
			$this->db->select('term.TermID');
			$this->db->from('term');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->where('term.TermName',$Searched_term);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row_array();
			}
		}

		public function baseform_get_term($baseFormID,$languageID,$searched_item)
		{
			$this->db->select('*');
			$this->db->limit(6);
			$this->db->from('baseform');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('baseform.BaseFormID',$baseFormID);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('term.TermName !=', $searched_item);
			$this->db->where('baseform.Deleted', 0);
            $this->db->where('translation.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_term($languageID,$searched_item)
		{
			$this->db->select('*,term.FauxAmis as term_FauxAmis');
			$this->db->from('term');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('term.TermName', $searched_item);
            $this->db->where('translation.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function prompt($word,$languageID)
		{
			$this->db->where('TermName',$word);
		    $query1 = $this->db->get('term');
		    if ($query1->num_rows() > 0)
		    {
		    	$termID = $query1->row()->TermID;

		    	if(isset($termID))
			    {
			    	$this->db->where('FKLanguageID',$languageID)->where('FKTermID',$termID)->where('Deleted',0);
				    $query = $this->db->get('translation');
				    if ($query->num_rows() < 1)
				    {
				    	return '2';
				    }
			    }
		    }
		    else
		    {
		    	return '1';
		    }
		}

		public function baseform_get_term_more($baseFormID,$languageID)
		{
			$this->db->select('*');
			$this->db->limit($this->row_count('term'),6);
			$this->db->from('baseform');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('baseform.BaseFormID',$baseFormID);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('baseform.Deleted', 0);
            $this->db->where('translation.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function related_words_ID($termID,$languageID)
		{
			$this->db->select('FKTermIDChild');
			$this->db->from('term');
			$this->db->join('termrelatestoterm', 'term.TermID = termrelatestoterm.FKTermIDParent','inner');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('term.Deleted', 0);
			$this->db->where('term.TermID', $termID);
			$subQuery  =  $this->db->get_compiled_select();

			/*$this->db->select('FKTermIDChild')->from('termrelatestoterm');
			$this->db->join('translation', 'translation.FKTermID = termrelatestoterm.FKTermIDChild AND translation.FKLanguageID = '.$languageID);
			$this->db->where('FKTermIDParent IN ('.$subQuery.')', NULL, FALSE);
			$subQuery2  =  $this->db->get_compiled_select();*/

			$this->db->select('TermName,TermID');
			$this->db->from('term');
			$this->db->where('TermID IN ('.$subQuery.')', NULL, FALSE);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
		}

		public function get_Terms_forAdd()
		{
			$this->db->select('*');
			$this->db->from('term');
			//$this->db->join('termrelatestoterm', 'term.TermID = termrelatestoterm.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		/*public function get_Terms_forTermHasBase($baseFormID)
		{
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->where('termhasbaseform.FKBaseValueID !=',$baseFormID);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}*/

		public function get_SingleTerm($termID)
		{
			$this->db->select('*,term.FauxAmis as term_FauxAmis');
			$this->db->from('term');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->where('term.TermID',$termID);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function get_TermID($termName)
		{
			$this->db->select('TermID');
			$this->db->from('term');
			$this->db->where('term.TermName',$termName);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row()->TermID;
			}
		}

		public function row_count($tableName)
		{
			$query = $this->db->get($tableName);
			return $query->num_rows();
		}

		public function get_terms($languageID)
		{
			/*$this->db->select("*,(select GROUP_CONCAT(BaseName SEPARATOR ', ') from baseform a
							Inner join termhasbaseform b ON b.FKBaseValueID = a.BaseFormID
							left join term c ON c.TermID = b.FKTermID
							where c.TermID = term.TermID) as Basenames");*/
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID AND translation.Deleted = 0 AND translation.FKLanguageID = '.$languageID,'left');
			//$this->db->where('(translation.FKLanguageID = '.$languageID.' OR translation.TranslationID IS NULL)');
			//$this->db->or_where('translation.TranslationID IS NULL', null);
			//$this->db->where('(translation.Deleted = 0 OR translation.Deleted IS NULL)');
			$this->db->where('term.Deleted',0);
			$this->db->order_by('TermName', 'ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function search_terms($searched_item,$languageID)
		{
			/*$this->db->select("*,(select GROUP_CONCAT(BaseName SEPARATOR ', ') from baseform a
							Inner join termhasbaseform b ON b.FKBaseValueID = a.BaseFormID
							left join term c ON c.TermID = b.FKTermID
							where c.TermID = term.TermID) as Basenames");*/
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID AND translation.Deleted = 0 AND translation.FKLanguageID = '.$languageID,'left');
			//$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->where('term.Deleted',0);
			//$this->db->where('translation.Deleted',0);
			//$this->db->where('translation.FKLanguageID',$languageID);
			$this->db->like('term.TermName',$searched_item);
			//$this->db->or_like('essay.DocumentReference',$searched_item);
			//$this->db->or_like('baseform.BaseName',$searched_item);
			$this->db->order_by('TermName', 'ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_first_termID($baseID)
		{
			$this->db->select('TermID');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKTermID = term.TermID','left');
			$this->db->join('baseform', 'baseform.BaseFormID = termhasbaseform.FKBaseValueID','left');
			$this->db->where('baseform.BaseFormID',$baseID);
			$this->db->where('term.Deleted','False');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row()->TermID;
			}
		}

		public function saveRecord($data,$tableName)
		{
			return $this->db->insert($tableName,$data);
		}

		public function updateAccount($data,$user_id)
		{
			$this->db->where('ID', $user_id);
			return $this->db->update('accounts', $data);
		}

		public function isTermhasBaseExist($baseID,$termID)
		{
			$this->db->where('termhasbaseform.FKBaseValueID',$baseID);
			$this->db->where('termhasbaseform.FKTermID',$termID);
			$query = $this->db->get('termhasbaseform');
			if($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function save_inflection($inflectionName,$baseID,$partofspeech)
		{
			$data = array('InflectionName' => $inflectionName);
			$this->db->insert('inflection',$data);

			$data = array('PartOfSpeechID' => $partofspeech,'FKBaseValueID' => $baseID, 'FKInflectionID' => $this->db->insert_id());
			return $this->db->insert('basehasinflection',$data);
		}

		public function manage_RelatedTerms($tags,$termID)
		{
			$arr = explode(',', $tags);
			foreach ($arr as $tag)
			{
				$FKTermIDChild = $this->get_TermID($tag);
				if(isset($FKTermIDChild))
				{
					$query = $this->db->query("SELECT TRUE FROM termrelatestoterm WHERE FKTermIDParent = ".$termID." AND FKTermIDChild = ".$FKTermIDChild." limit 1");
					$data = array('FKTermIDChild' => $FKTermIDChild, 'FKTermIDParent' => $termID);
					$query->num_rows() == 0 ? $this->db->insert('termrelatestoterm', $data) : false;
				}
			}
		}

		public function delete_RelatedTerms($tags,$termID)
		{
			$arr = explode(',', $tags);
			foreach ($arr as $tag)
			{
				$FKTermIDChild = $this->get_TermID($tag);
				if(isset($FKTermIDChild))
				{
					$query = $this->db->query("SELECT TRUE FROM termrelatestoterm WHERE FKTermIDParent = ".$termID." AND FKTermIDChild = ".$FKTermIDChild." limit 1");
					$data = array('FKTermIDChild' => $FKTermIDChild, 'FKTermIDParent' => $termID);
					$query->num_rows() == 1 ? $this->db->delete('termrelatestoterm', array('FKTermIDParent' => $termID, 'FKTermIDChild' => $FKTermIDChild))  : false;
				}
			}
		}

		public function save_term($data)
		{
			$term = array('TermName' => $data['TermName'],'GlossaryEntry' => $data['GlossaryEntry'],'FauxAmis' => $data['FauxAmis'],'AddedBy' => $this->session->userdata('ID'));
			$this->db->insert('term',$term);

			$TermID = $this->db->insert_id();

			//$termhasbaseform = array('FKBaseValueID' => $baseID,'FKTermID' => $TermID);
			//$this->db->insert('termhasbaseform',$termhasbaseform);

			$essay = array('Title' => $data['Title'],'DocumentReference' => $data['DocumentReference'], 'FKTermID' => $TermID, 'Note' => $data['Note'], 'ManualReference' => $data['ManualReference']);
			$this->db->insert('essay',$essay);

			$context = array('FKTermID' => $TermID,'ContextValue' => $data['ContextValue']);
			return $this->db->insert('context',$context);
		}

		public function save_suggestedTranslation($searchedTerm,$translationID,$translation,$fullName)
		{
			$TermID = '';
			$this->db->select('TermID');
			$this->db->from('Term');
			$this->db->where('TermName', $searchedTerm);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$TermID = $query->row()->TermID;
			}

			if(isset($TermID))
			{
				$suggestion = array('FKTermID' => $TermID,'FKLanguageID' => $translationID,'Translation' => $translation,'SuggestedBy' => $fullName);
				return $this->db->insert('suggestedtranslation',$suggestion);
			}
			else
			{
				return false;
			}
		}

		public function update_term($data,$termID)
		{
			$term = array('TermName' => $data['TermName'],'GlossaryEntry' => $data['GlossaryEntry'],'FauxAmis' => $data['FauxAmis']);
			$this->db->where('TermID', $termID);
			$this->db->update('term', $term); 

			$essay = array('Title' => $data['Title'],'DocumentReference' => $data['DocumentReference'], 'Note' => $data['Note'],'ManualReference' => $data['ManualReference']);
			$this->db->where('FKTermID', $termID);
			$this->db->update('essay', $essay);

			$context = array('ContextValue' => $data['ContextValue']);
			$this->db->where('FKTermID', $termID);
			return $this->db->update('context', $context);
		}

		public function update_inflection($inflectionName,$inflectionID,$partofspeech)
		{
			$inflection = array('InflectionName' => $inflectionName);
			$this->db->where('InflectionID', $inflectionID);
			$this->db->update('inflection', $inflection); 

			$partofspeech = array('PartOfSpeechID' => $partofspeech);
			$this->db->where('FKInflectionID', $inflectionID);
			return $this->db->update('basehasinflection', $partofspeech);
		}

		public function update_basename($data,$baseID)
		{
			$base = array('BaseName' => $data['BaseName']);
			$this->db->where('BaseFormID', $baseID);
			return $this->db->update('baseform', $base); 
		}

		public function update_translation($data,$translationID)
		{
			//$translation = array('BaseName' => $data['BaseName']);
			$this->db->where('TranslationID', $translationID);
			return $this->db->update('translation', $data); 
		}

		public function get_latestID($tableName,$IDname)
		{
			$this->db->select_max($IDname);
			$this->db->from($tableName);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row()->$IDname;
			}
		}

		public function get_partofspeech()
		{
			$this->db->select('*');
			$this->db->from('partofspeech');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_accounts()
		{
			$this->db->select('*');
			$this->db->from('accounts');
			$this->db->where('Deleted',0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_inflections($baseFormID)
		{
			$this->db->select('*');
			$this->db->from('baseform');
			$this->db->join('basehasinflection', 'basehasinflection.FKBaseValueID = baseform.BaseFormID','inner');
			$this->db->join('inflection', 'inflection.InflectionID = basehasinflection.FKInflectionID','inner');
			$this->db->join('partofspeech', 'partofspeech.PartOfSpeechID = basehasinflection.PartOfSpeechID','inner');
			$this->db->where('baseform.BaseFormID', $baseFormID);
			$this->db->order_by('InflectionName', 'ASC');
			$this->db->where('inflection.Deleted',0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_Singleinflection($inflectionID)
		{
			$this->db->select('*');
			$this->db->from('inflection');
			$this->db->join('basehasinflection','basehasinflection.FKInflectionID = inflection.InflectionID','left');
			$this->db->join('partofspeech', 'partofspeech.PartOfSpeechID = basehasinflection.PartOfSpeechID','inner');
			$this->db->where('inflection.InflectionID', $inflectionID);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function get_translations($termID)
		{
			$this->db->select('*');
			$this->db->from('translation');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID');
			$this->db->where('FKTermID', $termID);
			$this->db->where('translation.Deleted', 0);
			$this->db->order_by('Translation', 'ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_Singletranslation($translationID)
		{
			$this->db->select('*');
			$this->db->from('translation');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','left');
			$this->db->where('translation.Deleted', 0);
			$this->db->where('translation.TranslationID',$translationID);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function deleteRecord($record_id ,$data,$tableName,$fieldName)
		{
			return $this->db->where($fieldName,$record_id)->update($tableName,$data);
		}

		public function permanent_del($data,$tableName)
		{
			return $this->db->delete($tableName, $data);
		}

		public function can_login($username,$password)
		{
			$this->db->where('Username',$username);
			$this->db->where('Password',$password);
			$this->db->where('Deleted', 0);
			$query = $this->db->get('accounts');

			if($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function user_details($username,$password)
		{
			$this->db->where('Username',$username);
			$this->db->where('Password',$password);
			$query = $this->db->get('accounts');

			return $query->row_array();
		}
	}

?>