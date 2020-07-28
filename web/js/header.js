/**
 * Created by chao on 15-1-22.
 */



$(document).ready(function(){
    $("#second_wap").hide();

    $("#left_btn").click(function(){

        $("#first_wap").hide(1500);
        $("#second_wap").show(1500);
    });
    $("#right_btn").click(function(){
        $("#first_wap").show(1500);
        $("#second_wap").hide(1500);

    });

    $("#login").click(
        function(){
            var diag = new Dialog();/*固定宽高的信息弹出窗*/
            diag.Width = 520;
            diag.Height = 350;
            diag.Title = "会员登陆";
            diag.URL = "/login";
            diag.show();
        }
    )

    $("#register").click(
        function(){
            var diag = new Dialog();/*固定宽高的信息弹出窗*/
            diag.Width = 520;
            diag.Height = 580;
            diag.Title = "会员注册";
            diag.URL = "/register";
            diag.show();
        }
    )
});
