<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		td{
			padding-right: 5px;
			padding-left: 5px;
			padding-top: 0px;
			padding-bottom: 0px;
		}
		th{
			padding-right: 5px;
			padding-left: 5px;			
		}
	</style>
</head>
<body>
	<div>
		<center>
			<p style="margin: 0px;">This is an automatic email from YMPI’s MIRAI system.<br>Please do not reply to this address.</p>
		</center>
		<br>
		
		<p>
			Dear YMPI Team,<br>
			Ada Pengajuan Work Permit Baru oleh <?= $data['wpos_data']->company_name ?>, 
		</p>
		<div style="width: 80%; margin: auto;">
			<table style="border:1px solid black; width: 100%;">

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Informasi Vendor</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Nama Perusahaan</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->company_name ?></td>
				</tr>


				<tr>
					<td style="width:2%;padding:5px;text-align:left">Alamat Perusahaan</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->company_address ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Email Perusahaan</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->company_email ?></td>
				</tr>


				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Rencana Kunjugan</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Tanggal Dari</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->date_from ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Tanggal Sampai</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->date_to ?></td>
				</tr>


				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Penanggung Jawab</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Nama</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->company_pic ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Jabatan</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->jabatan ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">No HP</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->no_hp ?></td>
				</tr>



				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Detail Pekerjaan</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Jenis Pekerjaan</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->jenis_pekerjaan ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Deskripsi</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->deskripsi ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Lokasi</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->lokasi ?></td>
				</tr>


				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Potensi Bahaya & Ketentuan Kerja</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Bahaya yang mungkin timbul</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->bahaya ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Aspek Lingkungan Yang Mungkin Timbul</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->lingkungan ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Hal Hal Yang Perlu Dilaksanakan</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->prosedur ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Alat Safety yang harus dipakai dan tersedia di lokasi</td>

					<?php
					$safety = explode(',', $data['wpos_data']->safety);
					?>

					<td style="width:8%;text-align:left;padding:5px">
						Alamat Pemadam APAR : <b><?= $safety[0] ?> </b><br>
						Safety Belt/Full Body Harness : <b><?= $safety[1] ?> </b><br>
						Safety Helmet : <b><?= $safety[2] ?> </b><br>
						Safety Shoes : <b><?= $safety[3] ?> </b><br>
						Sarung Tangan : <b><?= $safety[4] ?> </b><br>
						Masker : <b><?= $safety[5] ?> </b><br>
						Kaca Mata Pelindung : <b><?= $safety[6] ?> </b><br>
						Celemek/Apron : <b><?= $safety[7] ?> </b><br>
						Tutup Muka/Face Shield : <b> <?= $safety[8] ?></b>
					</td>

				</tr>

				<?php
				$peringatan = explode(',', $data['wpos_data']->peringatan);
				?>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Papan Peringatan Yang Harus Dipasang</td>

					<td style="width:8%;text-align:left;padding:5px">
						Awas Bahaya Api : <b><?= $peringatan[0] ?></b> <br>
						Awas Bahaya Dari Atas : <b><?= $peringatan[1] ?></b> <br>
						Mesin Diperbaiki : <b><?= $peringatan[2] ?></b> <br>
						Awas Bahaya Listrik : <b><?= $peringatan[3] ?></b> <br>
						Awas Lantai Licin : <b><?= $peringatan[4] ?></b>
					</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Ketentuan-Ketentuan Lain </td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->ketentuan ?></td>
				</tr>


				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">PIC YMPI</td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Department Terkait</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->departemen ?></td>
				</tr>

				<tr>
					<td style="width:2%;padding:5px;text-align:left">Penanggung Jawab dari YMPI </td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->pic_ympi ?></td>
				</tr>



				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr style="background-color:#b464f5">
					<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Work Permit</td>
				</tr>


				<tr>
					<td style="width:2%;padding:5px;text-align:left">Jenis Work Permit</td>
					<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->work_permit ?></td>
				</tr>

				<?php if ($data['wpos_data']->work_permit != 'None') { ?>
					<tr>
						<td style="width:2%;padding:5px;text-align:left">Jenis Pekerjaan</td>
						<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->type ?></td>
					</tr>

					<tr>
						<td style="width:2%;padding:5px;text-align:left">Lokasi Pekerjaan</td>
						<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->location ?></td>
					</tr>


					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>

					<tr style="background-color:#b464f5">
						<td colspan="2" style="color:white;font-size:16px;text-align:left;padding:5px">Keterangan Tambahan</td>
					</tr>

					<tr>
						<?php if ($data['wpos_data']->work_permit == 'Height Permit') { ?>
							<td style="width:2%;padding:5px;text-align:left">Fall Protection</td>
						<?php } else if ($data['wpos_data']->work_permit == 'Hot Work Permit') { ?>
							<td style="width:2%;padding:5px;text-align:left">Sumber Gas Yang Mudah Terbakar Lainnya</td>
						<?php } else if ($data['wpos_data']->work_permit == 'Confined Space Permit') { ?>
							<td style="width:2%;padding:5px;text-align:left">Prosedur Komunikasi</td> 
						<?php } ?>


						<?php if ($data['wpos_data']->work_permit == 'Hot Work Permit') { ?>
							<?php
							$equipment = explode(',', $data['wpos_data']->question1);
							?>
							<td style="width:8%;text-align:left;padding:5px">
								Tabung gas/Acetylene apakah sudah dijauhkan : <b><?= $equipment[0] ?></b> <br>
								Selang gas sudah dalam kondisi safety : <b><?= $equipment[1] ?></b> <br>
								Instrument listrik yang berpotensial sudah dimatikan : <b><?= $equipment[2] ?></b> <br>
								Bahan-bahan lainnya yang mudah terbakar, seperti kayu, karton, kertas, dan plastik : <b><?= $equipment[3] ?></b>
							</td>

						<?php } else { ?>
							<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->question1 ?></td>
							?php } ?>
						</tr>

						<tr>

							<?php if ($data['wpos_data']->work_permit == 'Height Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Warning Sign</td>
							<?php } 
							else if ($data['wpos_data']->work_permit == 'Hot Work Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">ALAT PELINDUNG DIRI</td>
							<?php }
							else if ($data['wpos_data']->work_permit == 'Confined Space Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Equipment Rescue</td>
							<?php } ?>

							<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->question2 ?></td>
						</tr>

						<tr>
							<?php if ($data['wpos_data']->work_permit == 'Height Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Lifting Equipment</td>
							<?php } 
							else if ($data['wpos_data']->work_permit == 'Hot Work Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Warning Sign</td>
							<?php }
							else if ($data['wpos_data']->work_permit == 'Confined Space Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Training</td>
							<?php } ?>



							<?php if ($data['wpos_data']->work_permit == 'Height Permit') { ?>

								<?php
								$equipment = explode(',', $data['wpos_data']->question3);
								?>

								<td style="width:8%;text-align:left;padding:5px">
									Chain Block : <b><?= $equipment[0] ?></b> <br>
									Crane : <b><?= $equipment[1] ?></b> <br>
									Kerekan : <b><?= $equipment[2] ?></b> <br>
									Palang : <b><?= $equipment[3] ?></b>
								</td>

							<?php } else { ?>
								<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->question3 ?></td>
							<?php } ?>
						</tr>

						<tr>

							<?php if ($data['wpos_data']->work_permit == 'Height Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Jalan Masuk / Jalan Keluar</td>
							<?php }
							else if ($data['wpos_data']->work_permit == 'Hot Work Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Inspeksi / Pengecekan</td>
							<?php } 
							else if ($data['wpos_data']->work_permit == 'Confined Space Permit') { ?>
								<td style="width:2%;padding:5px;text-align:left">Lifting Equipment</td>
							<?php }

							if ($data['wpos_data']->work_permit == 'Confined Space Permit') {

								$equipment = explode(',', $data['wpos_data']->question4);
								?>

								<td style="width:8%;text-align:left;padding:5px">
									Chain Block : <b><?= $equipment[0] ?></b> <br>
									Crane : <b><?= $equipment[1] ?></b> <br>
									Tripod : <b><?= $equipment[2] ?></b>
								</td>

							<?php } else { ?>
								<td style="width:8%;text-align:left;padding:5px"><?= $data['wpos_data']->question4 ?></td>
							<?php } ?>

						</tr>


					<?php } } ?>

					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>

					<tr>
						<td style="width:2%;padding:5px;font-weight:bold">Persetujuan Vendor</td>
						<td style="width:8%;text-align:left;padding:5px;font-weight:bold"><?= $data['wpos_data']->vendor_accept ?></td>
					</tr>
				</table>
			</div>
			<br>
			<br>
			<center>

				Apakah anda akan menyetujui permintaan ini?
				<br>
				<table style="width: 50%">
					<tr>


						<th style="font-weight: bold; color: black;">
							<a style="text-align: center; background-color: #ccff90; text-decoration: none; color: black;"
							href="http://10.109.52.4/mirai/public/approval/wpos?&wpos_id={{$data['wpos_data']->id}}&code={{$data['dept']}}">Masuk ke Halaman Approval</a>
						</th>
					</tr>
				</table>
			<br>
			<p style="margin: 0px;">
				Check WPOS Report in MIRAI :
				<a href="https://10.109.52.4/mirai/public/index/wpos/report">WPOS MIRAI</a><br><br>
			</p>
			</center>
			<br>
			<p style="font-weight: bold; margin: 0px;">PT. Yamaha Musical Products Indonesia<br></p>
			<p style="font-size: 14px; margin: 0px;">Jl. Rembang Industri I/36 Kawasan industri PIER Pasuruan<br></p>

			<div style="width: 40%;" style="margin: 0px;">
				<table style="margin-top: 0px;">
					<tr>
						<th style="padding: 0px; border: none; width: 30%; text-align: left; font-weight: normal; font-size: 14px;">Phone</th>
						<th style="padding: 0px; border: none; width: 70%; text-align: left; font-weight: normal; font-size: 14px;">: 0343-740290</th>					
					</tr>
					<tr>
						<th style="padding: 0px; border: none; width: 30%; text-align: left; font-weight: normal; font-size: 14px;">Fax</th>
						<th style="padding: 0px; border: none; width: 70%; text-align: left; font-weight: normal; font-size: 14px;">: 0343-740291</th>					
					</tr>
				</table>
			</div>
		</div>
	</body>
	</html>