<?php if (!defined(BASEPATH)) exit('No direct script access allowed');
class Page extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('pagination_custom_v3');
        $this->load->helper('alert');
        $this->load->model("member_model");

    }

    public function _remap($method)
    {
//        페이지의 세그먼트 기준으로 배열값을 가져옴.
        $this->segs = $this->uri->segment_array();
//        ajax 가 아니면 헤더푸터 없음
            if($method->input->is_ajax_request()){
                if (method_exists($this, $method)){
//                    불러온 함수 명을 호출
                    $this -> {"{$method}"}();
                }
//             세그먼트 번호 4번째 값이 엑셀이면 엑셀로 호출하겠다.
            } else if (isset($this->segs[4]) && $this->segs[4] == "excel"){
                if(method_exists($this, $method)) $this -> {"{$method}"}();
//                위에 둘다 아니면 일반적인 페이지 해당하는 함수 호출 + 헤더 푸터 붙인다.
            } else {
                $this->load->view("/common/header");
                if (method_exists($this, $method)){
                    $this->{"{$method}"}();
                }
//                $this->output->enable_profiler(true);
                $this->load->view("/common/footer");
            }
    }

    function index() {} //end index

    //코드 많이 줄이는 가이드
    function member_list()
    {
        $input = array(); //$input을 배열로 사용하겠다를 눈에 띄게하기위해서
    //    submit 포스트로 들어왔을 때 액션값이 post냐 get이냐 검사 후 트루값을 키값을 받고 해당한 키값의 배열을 받는다 그 값을 자동적으로 인풋안에 키값 벨류 값으로 넣는다.
        foreach ($this->input->post_get(NULL, TRUE) as $key => $val) $input["{$key}"] = $val;
    //  $name = $this->input->post('name', true); 이 부분을 생략하기 위해서 위에 코드처럼 자동으로 배열로 받아오겠다.
    //    페이지 값이 현재 몇페이지며, 없으면 항상 1로 초기화
        if(!isset($input["page"])) $input["page"] = 1;
    //                                      페이지 사이즈 15도 가능
        if (!isset($input["pagelist"])) $input['pagelist'] = 30;
    //    해당 액션 메인 테이블 주체가 되는 테이블이 무엇이냐 값을 강제로 지정.
        $input["table"] = "tb_member";

    //    만들어놓은 접근 불가능 함수 temp_pagen 하면 외부에서 접근 가능한데 _temp_pagen 하면 내부에서 밖에 접근 못함
    //    파라미터 값으로 해당 불러오는 모델 명, 모델명의 함수 명, 인자값들 , 갯 으로 받아오겠다.
        $data = $this->_temp_pagen("member_model","member_list", $input, "get");
        $data['input'] = $input;

        print_r($data);
        //$this->load->view("/member/member_list_v", $data);
    }

    // 해당하는 함수(모델, 모델함수명, 파라미터 값, 갯이냐 시그먼트냐, 아무값도 안 줬으면 2)
    function _temp_pagen($model, $model_func, $input, $method = "get", $linkCnt = 2)
    {
    //    여러 모델을 불러올수있다 ! 모델 호출
        $this->load->model("{$model}");
    //    해당하는 모델 -> 모델의 함수 (인자값) 해당값 리턴
        $db_data = $this->{$model}->{$model_func}($input);
    //    linkCnt = 배열개수 / 배열 개수 끝에 항상 페이지 번호가 붙는다는  가정 하에 로직.
        if($linkCnt) {
            $i =1; $link_url="";
            while($linkCnt >= $i){
                $link_url = $link_url."/".$this->segs[$i];
                $i++;
            }
        }

        $total_count = $db_data['total_cnt'];
        $data['total_count'] = $total_count;

//        페이지네이션 만듬(core쪽에 있는 라이브러리 복붙 extend로 사용할수도있는데 코어는 바뀔수가있어서 사용 x(상속은 바뀌지않는다는 전재하에 사용))
        $config = $this->pagination_custom_v3->pagenation_bootstrap($input["page"], $total_count,
        $input['pagelist'], $link_url, $linkCnt++, $num_link=3);

        if($method == "segment") $config['page_query_string'] = false; //쿼리 스트링 온 오프
        $config['page_query_string'] = true;

        $this->pagination_custom_v3->initialize($config);
        $data['page_nation'] = $this->pagination_custom_v3->create_links();
        $data['lists'] = $db_data['page_list_m'];

    //    print_r($data['page_nation']);
        return $data;
    }
}