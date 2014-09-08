<?php
class Controller_Phong extends Controller_Template
{

	public function action_index()
	{
		$data['phongs'] = Model_Phong::find('all');
		$this->template->title = "Phongs";
		$this->template->content = View::forge('phong/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('phong');

		if ( ! $data['phong'] = Model_Phong::find($id))
		{
			Session::set_flash('error', 'Could not find phong #'.$id);
			Response::redirect('phong');
		}

		$this->template->title = "Phong";
		$this->template->content = View::forge('phong/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Phong::validate('create');

			if ($val->run())
			{
				$phong = Model_Phong::forge(array(
				));

				if ($phong and $phong->save())
				{
					Session::set_flash('success', 'Added phong #'.$phong->id.'.');

					Response::redirect('phong');
				}

				else
				{
					Session::set_flash('error', 'Could not save phong.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Phongs";
		$this->template->content = View::forge('phong/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('phong');

		if ( ! $phong = Model_Phong::find($id))
		{
			Session::set_flash('error', 'Could not find phong #'.$id);
			Response::redirect('phong');
		}

		$val = Model_Phong::validate('edit');

		if ($val->run())
		{

			if ($phong->save())
			{
				Session::set_flash('success', 'Updated phong #' . $id);

				Response::redirect('phong');
			}

			else
			{
				Session::set_flash('error', 'Could not update phong #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('phong', $phong, false);
		}

		$this->template->title = "Phongs";
		$this->template->content = View::forge('phong/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('phong');

		if ($phong = Model_Phong::find($id))
		{
			$phong->delete();

			Session::set_flash('success', 'Deleted phong #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete phong #'.$id);
		}

		Response::redirect('phong');

	}

}
