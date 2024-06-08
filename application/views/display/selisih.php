<?php
						 function selisih($jam_a,$jam_b){
						list ($h,$m,$s)= explode (":",$jam_a);
						$dtAwal= mktime($h,$m,$s,"1","1","1");
						list ($h,$m,$s)= explode (":",$jam_b);
						$dtAkhir= mktime($h,$m,$s,"1","1","1");
						$dtSelisih = $dtAkhir-$dtAwal;
						$totalmenit=$dtSelisih/60;
						$jam=explode(".",$totalmenit/60);
						$sisamenit=($totalmenit/60)-$jam[0];
						$sisamenit2=$sisamenit*60;
						return "$jam[0] ";//jam $sisamenit2 menit
						} ?>