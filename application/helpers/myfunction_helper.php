<?php

if (! function_exists('hitung_umur')) {
    function hitung_umur($tgl)
    {
        $tanggal = new DateTime($tgl);
        $today = new DateTime('today');
        $y = $today->diff($tanggal)->y;
        $m = $today->diff($tanggal)->m;
        $d = $today->diff($tanggal)->d;
        return $y . " Tahun, " . $m . " Bulan, " . $d . " Hari";
        //return $y . " , " . $m;
    }
}

function anti_injection($data)
{
	$filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
	return $filter;
}

function slug($s)
{
	$c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d

    $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
}


function rupiah($nominal)
{
	return 'Rp '.number_format($nominal, 0, ',', '.');
}

/** login codeIgniter menggunakan bycrypt **/

if(!function_exists('get_hash'))
{    
    function get_hash($PlainPassword)
    {
    	$option=[
                'cost'=>5,// proses hash sebanyak: 2^5 = 32x
    	        ];
    	return password_hash($PlainPassword, PASSWORD_DEFAULT, $option);
   }
}

if(!function_exists('hash_verified'))
{  
    function hash_verified($PlainPassword,$HashPassword)
    {
    	return password_verify($PlainPassword,$HashPassword) ? true : false;
   }
}

/** login codeIgniter menggunakan bycrypt **/
     function show_ok_msg($content='', $size='14px') {
		if ($content != '') {
			return   $content;
		}
	}

    function show_del_msg($content='', $size='14px') {
		if ($content != '') {
			return   $content;
		}
	}
	function show_err_msg($content='', $size='20px') {
		if ($content != '') {
			return   $content;
		}
	}
	function show_err_msg2($content='', $size='14px') {
		if ($content != '') {
			return   '<p class="box-msg">
				      <div class="alert alert-danger alert-dismissible">
					      <div class="info-box-icon">
					      	<i class="fa fa-warning"></i>
					      </div>
					      <div class="info-box-content" style="font-size:' .$size .'">
				        	' .$content
				      	.'</div>
					  </div>
				    </p>';
		}
	}
	
	// MODAL
	function show_my_print($content='', $id='', $data='', $size='') {
		$_ci = &get_instance();

		if ($content != '') {
			$view_content = $_ci->load->view($content, $data, TRUE);

			return '<div class="modal fade" id="' .$id .'" role="dialog">
					  <div class="modal-dialog modal-' .$size .'" role="document">
					    <div class="modal-content" style="border: none;">
					        ' .$view_content .'
					    </div>
					  </div>
					</div>';
		}
	}
	function show_my_modal($content='', $id='', $data='', $size='') {
		$_ci = &get_instance();

		if ($content != '') {
			$view_content = $_ci->load->view($content, $data, TRUE);

			return '<div class="modal fade" id="' .$id .'" role="dialog">
					  <div class="modal-dialog modal-' .$size .'" role="document">
					    <div class="modal-content">
					        ' .$view_content .'
					    </div>
					  </div>
					</div>';
		}
	}
	function show_my_display($content='', $id='', $data='', $size='') {
		$_ci = &get_instance();

		if ($content != '') {
			$view_content = $_ci->load->view($content, $data, TRUE);

			return $view_content ;
		}
	}

	function show_my_confirm($id='', $class='', $title='Konfirmasi', $yes = 'Ya', $no = 'Tidak') {
		$_ci = &get_instance();

		if ($id != '') {
			echo '
			<div class="modal fade" id="' .$id .'">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header bg-yellow">
						<h4 class="modal-title">PERINGATAN !!</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</div>
								<div class="modal-body">
								<p>' .$title .'</p>
								</div>
								<div class="modal-footer justify-content-between">
								
								<button class="btn btn-sm btn-primary ' .$class .'"> <i class="fas fa-hand-paper"></i> ' .$yes .'</button>
									<button class="btn btn-sm btn-danger float-right" data-dismiss="modal"> <i class="fas fa-times"></i> ' .$no .'</button>
						</div>
					</div>
				</div>
			</div>';
		}
	}