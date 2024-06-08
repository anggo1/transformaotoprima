<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_mesinabsen extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('parse');
    }
	public function select_mesin() {
		$sql = " SELECT *
		FROM tbl_hrd_mesin";

		$data = $this->db->query($sql);

		return $data->result();
	}
    public function select_id_mesin($id) {
		$sql = " SELECT * FROM tbl_hrd_mesin WHERE id='{$id}'";

		$data = $this->db->query($sql);

		return $data->result();
	}
    public function insertMesin($data) {
		$sql = "INSERT INTO tbl_hrd_mesin VALUES
		('','".$data['ip']."','".$data['pass']."','".$data['nama_mesin']."')";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    public function updateMesin($data) {
		$sql = "UPDATE tbl_hrd_mesin SET ip='" .$data['ip'] ."',pass='" .$data['pass'] ."',nama_mesin='" .$data['nama_mesin'] ."'
        WHERE id='" .$data['id'] ."'";

		$this->db->query($sql);

		return $this->db->affected_rows();
	}
    function deleteDev($id)
    {
        $sql = "DELETE FROM tbl_hrd_mesin WHERE id='{$id}'";
        
		$this->db->query($sql);

		return $this->db->affected_rows();
    }

	public function if_exist_check($PIN, $DateTime){
        $data = $this->db->get_where('tbl_hrd_data_absen', array('pin' => $PIN, 'date_time' => $DateTime))->row();
        return $data;
    }
	

	public function get_data_absen($IP,$key){
		error_reporting(0);
        //$IP = $this->get_setting()->ip;
        //$Key = $this->get_setting()->password;
        $IP = $IP;
        $Key = $key;
        if($IP!=""){
        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
            if($Connect){
                $soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
                $newLine="\r\n";
                fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
                fputs($Connect, "Content-Type: text/xml".$newLine);
                fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
                fputs($Connect, $soap_request.$newLine);
                $buffer="";
                while($Response=fgets($Connect, 1024)){
                    $buffer=$buffer.$Response;
                }
                $buffer = Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
                $buffer = explode("\r\n",$buffer);
                for($a=0;$a<count($buffer);$a++){
                    $data = Parse_Data($buffer[$a],"<Row>","</Row>");
                    $PIN = Parse_Data($data,"<PIN>","</PIN>");
                    $DateTime = Parse_Data($data,"<DateTime>","</DateTime>");
                    $Verified = Parse_Data($data,"<Verified>","</Verified>");
                    $Status = Parse_Data($data,"<Status>","</Status>");
                    $ins = array(
                           "pin"       =>  $PIN,
                            "date_time" =>  $DateTime
                            );
                    if (!$this->if_exist_check($PIN, $DateTime) && $PIN && $DateTime) {
                    	$this->db->insert('tbl_hrd_data_absen', $ins);
                    }
					$dataAbsen= $data;
					echo ' <tr>
					<td>'.$PIN.'</td>
					<td>'.$DateTime.'</td>
					<td>'.$Verified.'</td>
					<td>'.$Status.'</td>
				</tr>';
                }
                if($buffer){
                	return '<div class="alert alert-success alert-dismissable">
        				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        				<h4><i class="icon fa fa-check"></i> Success !</h4>
        				Anda terhubung dengan mesin.
        			</div>';
                } else {
                	return '<div class="alert alert-danger alert-dismissable">
        				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        				<h4><i class="icon fa fa-ban"></i> Alert!</h4>
        				Anda tidak terhubung dengan mesin !
        			</div>';
                }
            }
        } 
    }
	public function clear_data(){
		$IP = $data['ip'];
        $Key = $data['pass'];
        if($IP!=""){
        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
            if($Connect){
				 if($Connect){
					$soap_request = "<ClearData><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
					$newLine = "\r\n";
					fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
					fputs($connect, "Content-Type: text/xml" . $newLine);
					fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
					fputs($connect, $soap_request . $newLine);
					$buffer = "";
					while($Response = fgets($connect, 1024)) {
						$buffer = $buffer . $Response;
						
					}
				} else {
					echo "Koneksi Gagal";
				}

				include("parse.php");
				$buffer = Parse_Data($buffer, "<Information>", "</Information>");
				echo "<b>Result:</b><br>";
				echo $buffer;
			}
		}
	}
	public function upload_nama($data){	
		$IP = $data['ip'];
        $Key = $data['pass'];
        if($IP!=""){
        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
		if($connect) {
			$id = $data['nip'];
			$nama = $data['nama'];
			$soap_request = "<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN>" . $id . "</PIN><Name>" . $nama . "</Name></Arg></SetUserInfo>";
			$newLine = "\r\n";
			fputs($connect, "POST /iWsService HTTP/1.0" . $newLine);
			fputs($connect, "Content-Type: text/xml" . $newLine);
			fputs($connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
			fputs($connect, $soap_request . $newLine);
			$buffer = "";
			while($Response = fgets($connect, 1024)) {
				$buffer = $buffer . $Response;
			}
		} else {
			echo "Koneksi Gagal";
		}
		include("parse.php");
		$buffer = Parse_Data($buffer, "<Information>", "</Information>");
		echo "<b>Result:</b><br>";
		echo $buffer;
	}
}

	

}

/* End of file Mod_pegawai.php */