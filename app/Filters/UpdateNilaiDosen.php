<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\Admin\Mahasiswa;
use App\Filters\UpdateNilaiDefault;
use \DateTime;
use App\Libraries\Access_API; // Import library
use App\Libraries\QRcodelib; // Import library
use CodeIgniter\Database\Query;
use Dompdf\Dompdf;
use Dompdf\Options;
use Google\Service\AdExchangeBuyerII\Date;

class UpdateNilaiDosen implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $this->nilai_update = new UpdateNilaiDefault;
        $this->api = new Access_API();
        $this->qr = new QRcodelib();
        $db = \Config\Database::connect();


        $nip_dosen = session()->get('ses_id');
        $data_mhs_bimbingan_dosen = $db->query("SELECT nim FROM `tb_pengajuan_pembimbing` WHERE nip ='$nip_dosen' AND status_pengajuan='diterima' AND pesan IS NULL ")->getResult();
        $data_mhs_uji_dosen = $db->query("SELECT DISTINCT nim FROM `tb_penguji` WHERE nip='$nip_dosen' AND status='aktif'")->getResult();

        foreach ($data_mhs_bimbingan_dosen as $key) {
            $nim_mhs = $key->nim;
            // $this->ass->ecas($nim_mhs);
            $this->nilai_update->update($nim_mhs);
        }

        foreach ($data_mhs_uji_dosen as $key) {
            $nim_mhs = $key->nim;
            $this->nilai_update->update($nim_mhs);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
