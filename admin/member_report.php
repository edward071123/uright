<?php
    include "includes/init.php";
	include "includes/_chk.php";

    if(isset($_GET['getdate'])){
        $g_date = $_GET['getdate'];
    }else{
        $g_date = date("Y-m");
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$_CSY["title"];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Robots" content="all" />
<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="js/jPaginate/css/style.css"/>
<link rel="stylesheet" type="text/css" href="js/jQuery-Impromptu/default.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.0.0.min.js"></script>
<script type="text/javascript" src="js/jQuery-Impromptu/jquery-impromptu.js"></script>
<script type="text/javascript" src="js/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="js/list_init.js"></script>
<link rel="stylesheet" type="text/css" href="../jquery-ui-1.10.4.custom/css/redmond/jquery-ui-1.10.4.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../grid/css/ui.jqgrid.css"/>
<script src="../grid/js/i18n/grid.locale-tw.js" type="text/javascript"></script>
<script src="../grid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript">

    $(function(){
        var today = new Date("<?=$g_date?>");
        var mm = today.getMonth()+1; //January is 0!
        if (mm < 10) {
            mm = '0' + mm;
        }
        var yyyy = today.getFullYear();
        $("#birthday").val(yyyy+"-"+mm);
		$("#birthday").datepicker({
            showOn: 'button',
            buttonText: '選擇月份',
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm-yy',
            showButtonPanel: true,
            monthNamesShort: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
            closeText: '選擇',
            currentText: '本月',
            isSelMon:'true',
            defaultDate : new Date(),
            yearRange : "2015:new Date()",
            onClose: function (dateText, inst) {
                var month = +$("#ui-datepicker-div .ui-datepicker-month :selected").val() + 1,
                    year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                if (month < 10) {
                    month = '0' + month;
                }
                this.value = year + '-' + month;
                if (typeof this.blur === 'function') {
                    this.blur();
                    location.href = 'member_report.php?getdate='+this.value;
                }

            }
        });
        var g_date = "<?=$g_date?>";
        $("#list_level").jqGrid({
            url:'get_list_month_count.php',
            datatype: "json",
		    mtype: 'GET',
           	colNames:['序','身分證字號','會員姓名','母球實際善款點數(80%)','子球實際善款點數(80%)','實際善款總點數(80%)','本月招募人數',
            '本月行政費15%總計','本月行政費9%總計','本月組長基數配點總計','分配行政作業費點數(70%)','本月可用點數','本月組長收入','本月組長支出'],
           	colModel:[
           		{name:'no',index:'no', width:10, sortable:false,align:'center'},
                {name:'idn',index:'idn', width:60, sortable:false,align:'center'},
                {name:'m_name',index:'m_name', width:40, sortable:false,align:'center'},
                {name:'m_s_eight',index:'m_s_eight', width:90, sortable:false,align:'center'},
                {name:'m_o_eight',index:'m_o_eight', width:90, sortable:false,align:'center'},
                {name:'m_eight',index:'m_eight', width:90, sortable:false,align:'center'},
				{name:'m_intro',index:'m_intro', width:30, sortable:false,align:'center'},
				{name:'m_a',index:'m_a', width:80, sortable:false,align:'center'},
                {name:'m_b',index:'m_b', width:80, sortable:false,align:'center'},
                {name:'m_div',index:'m_div', width:90, sortable:false,align:'center'},
                {name:'m_seven',index:'m_seven', width:105, sortable:false,align:'center'},
                {name:'m_total',index:'m_total', width:60, sortable:false,align:'center'},
                {name:'m_product_i',index:'m_product_i', width:60, sortable:false,align:'center'},
                {name:'m_product_e',index:'m_product_e', width:60, sortable:false,align:'center'}
           	],
            postData:{g_date:g_date},
           	rowNum:100000,
            pgbuttons:false,
            //grouping:true,
         //   	groupingView : {
         //   		groupField : ['cla'],
            //     groupText : ['<b>{0} - {1} 人</b>'],
            //     groupCollapse : true,
         //   	},
            height: "1000px",
            autowidth:true,
           	pager: '#pager_level',
           	sortname: 'no',
            viewrecords: true,
            loadonce: true,
            sortorder: "asc",
            caption:"會員點數發放列表",
            loadComplete: function () {

            },
            loadError: function (jqXHR, textStatus, errorThrown) {
                console.log('HTTP status code: ' + jqXHR.status + '\n' +
                      'textStatus: ' + textStatus + '\n' +
                      'errorThrown: ' + errorThrown);
                console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
            }
        }).navGrid("#pager_account",{edit:false,add:false,del:false,search:false});
        $("#list_company").jqGrid({
            url:'get_list_company.php',
            datatype: "json",
		    mtype: 'GET',
           	colNames:['本月安置自強單位(母球)','本月安置自強單位(子球)','本月安置自強單位總計','本月應收',
            '本月十層獎金點數支出','本月十層獎金發放剩餘','組長行政作業支出',
            '組長行政作業剩餘','本月補助點數剩餘','本月組長收入','本月組長支出'],
           	colModel:[
           		{name:'menber_basic',index:'menber_basic', width:90, sortable:false,align:'center'},
                {name:'menber_add',index:'menber_add', width:90, sortable:false,align:'center'},
                {name:'menber_total',index:'menber_total', width:90, sortable:false,align:'center'},
                {name:'money_total',index:'money_total', width:100, sortable:false,align:'center'},
                {name:'money_ten_e',index:'money_ten_e', width:90, sortable:false,align:'center'},
                {name:'money_ten_r',index:'money_ten_r', width:100, sortable:false,align:'center'},
                {name:'money_account_e',index:'money_account_e', width:70, sortable:false,align:'center'},
                {name:'money_account_r',index:'money_account_r', width:100, sortable:false,align:'center'},
                {name:'money_total_r',index:'money_total_r', width:100, sortable:false,align:'center'},
                {name:'m_product_i',index:'m_product_i', width:60, sortable:false,align:'center'},
                {name:'m_product_e',index:'m_product_e', width:60, sortable:false,align:'center'}
            ],
            postData:{g_date:g_date},
           	rowNum:10,
            height: "100px",
            autowidth:true,
           	pager: '#pager_company',
           	sortname: 'no',
            sortorder: "asc",
            caption:"協會各項數據統計表",
            loadComplete: function () {

            },
            loadError: function (jqXHR, textStatus, errorThrown) {
                console.log('HTTP status code: ' + jqXHR.status + '\n' +
                      'textStatus: ' + textStatus + '\n' +
                      'errorThrown: ' + errorThrown);
                console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
            }
        }).navGrid("#pager_company",{edit:false,add:false,del:false,search:false});
        $("#list_top").jqGrid({
            url:'get_company_account.php',
            datatype: "json",
		    mtype: 'GET',
           	colNames:['福委會自強單位母球實際善款點數(80%)','福委會自強單位子球實際善款點數(80%)',
            '福委會自強單位母球分配行政作業費點數(70%)','福委會帳戶可用善款數','扶弱經費','教育經費','行政經費','急難經費'],
           	colModel:[
           		{name:'menber_basic',index:'menber_basic', width:140, sortable:false,align:'center'},
                {name:'menber_add',index:'menber_add', width:140, sortable:false,align:'center'},
                {name:'money_total_seven',index:'money_total_seven', width:160, sortable:false,align:'center'},
                {name:'money_total',index:'money_total', width:110, sortable:false,align:'center'},
                {name:'money_total_for',index:'money_total_for', width:50, sortable:false,align:'center'},
                {name:'money_total_tw',index:'money_total_tw', width:50, sortable:false,align:'center'},
                {name:'money_total_ten',index:'money_total_ten', width:50, sortable:false,align:'center'},
                {name:'money_total_thi',index:'money_total_thi', width:50, sortable:false,align:'center'}

           	],
            postData:{g_date:g_date},
           	rowNum:100000,
            pgbuttons:false,
            height: "100px",
            autowidth:true,
           	pager: '#pager_top',
           	sortname: 'no',
            viewrecords: false,
            sortorder: "asc",
            caption:"福委會可用善款數",
            loadComplete: function () {

            },
            loadError: function (jqXHR, textStatus, errorThrown) {
                console.log('HTTP status code: ' + jqXHR.status + '\n' +
                      'textStatus: ' + textStatus + '\n' +
                      'errorThrown: ' + errorThrown);
                console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
            }
        }).navGrid("#pager_top",{edit:false,add:false,del:false,search:false});
        $("#list_top1").jqGrid({
            url:'get_company_account_ad.php',
            datatype: "json",
		    mtype: 'GET',
           	colNames:['福委會自強單位(母球+子球)十層挹注收入給自己(10%)','福委會自強單位(母球+子球)行政挹注收入給自己(15%)',
            '其他所有自強單位十層挹注福委會點數(10%)','其他所有自強單位行政挹注福委會點數(15%)'],
           	colModel:[
                {name:'money_s_ten_ten',index:'money_s_ten_ten', width:100, sortable:false,align:'center'},
                {name:'money_s_fif_ac',index:'money_s_fif_ac', width:100, sortable:false,align:'center'},
                {name:'menber_ten',index:'menber_ten', width:100, sortable:false,align:'center'},
                {name:'money_fifteen',index:'money_fifteen', width:100, sortable:false,align:'center'}
           	],
            postData:{g_date:g_date},
           	rowNum:100000,
            pgbuttons:false,
            height: "100px",
            autowidth:true,
           	pager: '#pager_top1',
           	sortname: 'no',
            viewrecords: false,
            sortorder: "asc",
            caption:"福委會被挹注點數",
            loadComplete: function () {

            },
            loadError: function (jqXHR, textStatus, errorThrown) {
                console.log('HTTP status code: ' + jqXHR.status + '\n' +
                      'textStatus: ' + textStatus + '\n' +
                      'errorThrown: ' + errorThrown);
                console.log('HTTP message body (jqXHR.responseText): ' + '\n' + jqXHR.responseText);
            }
        }).navGrid("#pager_top1",{edit:false,add:false,del:false,search:false});
	});
</script>
</head>

<body>


<div id="container">
    <?php include("includes/_menu.php") ?>
    <div id="content" style="padding-bottom: 60px;">
        <div class="breadcrumb"></div>
        <div class="box">
            <div class="heading">
                <h1 style="background-image: url('image/user.png');">福委會後台管理總表</h1>
            </div>
            <div class="content">
                <table class="form" >
                    <tr>
                        <td width="200">選擇月份:</td>
                    	<td><input type="text" name="birthday" id="birthday" /></td>
                    </tr>
                </table>
                <br>
                <div style="margin-bottom: 40px; font-size:20px; ">
                <span style="background-color: #cd0a0a;">(以下欄位係根據本註解區說明而來，請注意其欄位涵義)</span><p>
                本月應收 = 3600 x 安置自強單位總計 <p>
                組長行政作業 (組長行政作業補助點數合計) = 所有會員的 A + B + 組長基數配發點數 <p>
                本月十層獎金發放剩餘 = 2500 x 安置自強單位總計 - 十層獎金點數支出 <p>
                組長行政作業點數剩餘 = 1100 x 安置單位總計 - 組長行政作業支出<p>
                本月應收 = 本月十層獎金點數支出 + 組長行政作業支出 + < 本月補助點數剩餘 = 組長行政作業剩餘 +  本月十層獎金點發放剩餘><p>
                福委會帳戶可用善款數 = 扶弱經費(可用善款數40%) + 教育經費(可用善款數20%) + 行政經費(可用善款數10%) + 難經費(可用善款數30%)<p>
                分配行政作業費點數(70%) = 該會員的 A來源收入(15%) + B來源收入(9%) + 組長基數配發點數<p>

                </div>
                <a href="export_as_all.php?getdate=<?=$g_date?>" class="button" ><span>匯出協會各項數據統計表excel
                </span></a>
                <table id="list_company"></table>
                <div id="pager_company"></div>
                <P>
                <a href="export_use_money.php?getdate=<?=$g_date?>" class="button" ><span>匯出福委會可用善款數excel</span></a>
                <table id="list_top"></table>
                <div id="pager_top"></div>
                <P>
                <a href="export_income.php?getdate=<?=$g_date?>" class="button" ><span>匯出福委會被挹注點數excel</span></a>
                <table id="list_top1"></table>
                <div id="pager_top1"></div>
                <p>
                <a href="export_month_count.php?getdate=<?=$g_date?>" class="button" ><span>匯出會員總表excel</span></a>
                <a href="export_month_count_simple.php?getdate=<?=$g_date?>" class="button" ><span>匯出會員簡表excel</span></a>

                <table id="list_level"></table>
                <div id="pager_level"></div>
            </div>
        </div>
    </div>
</div>
<?php include "includes/_footer.php" ?>
</body>

</html>
