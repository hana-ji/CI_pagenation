<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Member_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

//    함수 member_list 받았고 인자값으로 인풋값(배열로 몽땅 받음->배열값으로 조건을 처리하면됨.)
    function member_list($input)
    {
//        페이지 개수로 페이지를 보냈는데 그 값이 넘버가 아닌경우 무조건 1로 처리
        if(is_numeric($input["page"]) == false) $input["page"] = 1;
//        페이지가 -1 일때도 에러나 나기때문에 그거 방지로 1 처리
        if($input["page"] < 0) $input["page"] =1;

//        현재 페이지 -1 곱하기 페이지 리스
        $limit_ofset = ($input["page"]-1) * $input["pagelist"];

//        총 카운터 구하기 위해서 slect count(*) 로 구하는 거보다 빠름(index타기때문에)
        $this->db->select('SQL_CALC_FOUND_ROWS T1.*', false);
        $this->db->from($input['table']."as T1");

//        조건 / 그누보드 방식 (질문하는 카테고리와 검색하는 카테고리가 무엇이냐 있고 && 있고 && 있으면)
        if(isset($input["sfl"]) && $input["sfl"] && $input["stx"] && $input["stx"]){
//        체크한다. 그 값이 이메일이면 like ex) a면 %a% 이런식으로 검색하겠다.
            if($input["sfl"] == "email") $this->db->like("T1.email", $input["stx"]);
                if($input["sfl"] == "hpp") $this->db->like("T1.hpp", $input["stx"]);
        }
//        시작일과 종료일 값을 받아왔고 값이 있으면 생성일 기준으로 스타트데이트/ 엔드일 기준으로 엔드데이트(는 항상 +1)
        if (isset($input["sdate"]) && $input["sdate"]) $this->db->where("T1.create_datetime >=", $input["sdate"]);
        if (isset($input["edate"]) && $input["edate"]) $this->db->where("T1.create_datetime <=", date('Y-m-d',
        strtotime($input["edate"].'+1 day')));

//        정렬/ 생성일 기준으로 정렬 조건 더 주면 다른식으로도 정렬 ㅇㅇ
        $this->db->order_by("T1.create_datetime", "desc");

//        위에 limit 절 limit(현재페이지리스트(기본 30)), 페이지가 1이면 1 2이면 2 3이면 3)
        $this->db->limit($input["pagelist"],$limit_ofset);

//        result_array로 현재 값 다 집어넣음
        $result['page_list_m']= $this->db->get()->result_array();
//        토탈값은 위에서 index 태운값(SQL~~~ 뭐시기) 받아옴(FOUND_ROW로) total_cnt로 받아서 쿼리실행 후 row에 토탈카운트로 반환
        $result['total_cnt'] =$this->db->query("SELECT FOUND_ROWS() AS total_cnt;")->row()->total_cnt;
        return $result;
    }
}