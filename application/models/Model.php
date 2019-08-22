<?php
	class Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    // 全体CVRレポートのデータ取得
    public function getAllCvrInfo_db() 
    {
        $query = "SELECT * FROM cvr_info";
        return $this->db->query($query)->result();
    }

    // 一日前のCVRレポートのデータ取得
    public function getCvrInfo_db($dateOfYesterday) 
    {
        $query = "SELECT * FROM cvr_info where DATE(date) = '$dateOfYesterday'";
        return $this->db->query($query)->result();
    }

    // 一日前の注文数のデータを取得
    public function getOrderSum($dateOfYesterday)
    {
        $query = "SELECT COUNT(*) as count FROM order_main where DATE(od_date) = '$dateOfYesterday'";
        return $this->db->query($query)->result();
    }

    // CVRレポートのデータを挿入する
    public function insert_cvr($cvr_data)
    {
        $date = $cvr_data['date'];
        $count_pv = $cvr_data['count_pv'];
        $count_uu = $cvr_data['count_uu'];
        $count_order = $cvr_data['count_order'];
        $cvr = $cvr_data['cvr'];

        $query = "INSERT INTO cvr_info (date, count_pv, count_uu, count_order, cvr) VALUES ('$date', '$count_pv', '$count_uu', '$count_order', '$cvr')";
        return $this->db->query($query);
    }

    // 注文数によるすべての商品のランキング
    public function rankData() 
    {
        $query = "SELECT oi.pd_no, SUM(oi.od_qty) as sum, oi.od_no, om.od_date, pi.pd_name, pi.pd_img
                FROM order_info as oi 
                JOIN order_main as om
                ON om.od_no = oi.od_no
                JOIN product_info as pi
                ON oi.pd_no = pi.pd_no
                GROUP BY oi.pd_no 
                ORDER BY sum DESC
            ";
        return $this->db->query($query)->result();
    }

    // 注文数による商品のランキング（日付別）
    public function rankDataY($date) 
    {
        $query = "SELECT oi.pd_no, SUM(oi.od_qty) as sum, oi.od_no, om.od_date, pi.pd_name, pi.pd_img
                FROM order_info as oi 
                JOIN order_main as om
                ON om.od_no = oi.od_no
                JOIN product_info as pi
                ON oi.pd_no = pi.pd_no
                WHERE DATE(om.od_date) = '$date'
                GROUP BY oi.pd_no 
                ORDER BY sum DESC
            ";
        return $this->db->query($query)->result();
    }
}
?>