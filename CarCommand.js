var userID = 0;
var deviceID = 0;
var timeZone = "";
var deviceName = "";
var model = 0;
var userName = "";
var sn = "";
var loginType = 0;
var loginDeviceID = 0;

$(document).ready(function () {

    userID = parseInt($("#hidUserID").val());
    deviceID = parseInt($("#hidDeviceID").val());
    timeZone = $("#hidTimeZone").val();
    deviceName = $("#hidDeviceName").val();
    model = parseInt($("#hidModel").val());
    userName = $("#hidUserName").val();
    sn = $("#hidSerialNumber").val();
    loginType = parseInt($("#hidLoginType").val());
    loginDeviceID = deviceID;

    if (loginType == 1) {
        $("#spanUserName").html(userName);
    } else {
        $("#spanUserName").html(deviceName);
    }

    $("#spanPassDeviceName").html(deviceName);
    $("#sendBtn").html(" " + allPage.confirm + " ");
    initCmd();

    if (model == 510) {
        $("#sel808ZDBJ").val($("#hidCellPhone2").val());
        $("#sel808DGXC").val($("#hidCellPhone3").val());
        $("#sel808SYXC").val($("#hidSOSPhone1").val());
        $("#sel808SCJG2").val($("#hid3GSCJG").val());
    }
});

function initCmd() {
    $(".sec-wrap").hover(function () {
        $(".sec-list", this).show();
    }, function () {
        $(".sec-list", this).hide();
    });
    $("#ulSetCommand").html(getCarCommandList(1));
    $("#ulControlCommand").html(getCarCommandList(2));
    $("#ulQueryCommand").html(getCarCommandList(3));
    $("#sec-control").show();
    if (model != 110) {
        $("#sec-set").show();
    }
    if (model == 12) {
        $("#sec-query").show();
    }
}

function getCarCommandList(t) {
    var secControlArr = [];
    if (model == 72) {
        if (t == 1) {
            var opt3 = {};
            opt3.name = yiwen201409.adminPhone;
            opt3.links = [{ type: "S7101", txt: yiwen201409.adminPhone}];
            secControlArr.push(menuLine2(opt3));



        } else if (t == 2) {

            var opt3 = {};
            opt3.name = mapPage.deviceFortify;
            opt3.links = [{ type: "SCF0", txt: mapPage.deviceFortify}];
            secControlArr.push(menuLine2(opt3));

            var opt4 = {};
            opt4.name = "基站塔桅倾斜告警阀值";
            opt4.links = [{ type: "ANGLE", txt: "基站塔桅倾斜告警阀值"}];
            secControlArr.push(menuLine2(opt4));
            var opt5 = {};
            opt5.name = "基站塔桅摆动幅度告警阀值";
            opt5.links = [{ type: "RANGE", txt: "基站塔桅摆动幅度告警阀值"}];
            secControlArr.push(menuLine2(opt5));
            var opt6 = {}; //RANGE  FREQUENCY  DISTANCE
            opt6.name = "基站塔桅摆动频率告警阀值";
            opt6.links = [{ type: "FREQUENCY", txt: "基站塔桅摆动频率告警阀值"}];
            secControlArr.push(menuLine2(opt6));
            var opt7 = {};
            opt7.name = "基站塔桅水平偏移阀值";
            opt7.links = [{ type: "DISTANCE", txt: "基站塔桅水平偏移阀值"}];
            secControlArr.push(menuLine2(opt7));


        }
    } else if (model == 92) {
        if (t == 2) {
            var opt1 = {};
            opt1.name = "开启一级锁车";
            opt1.links = [{ type: "808YJSC", txt: "开启一级锁车"}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = "开启二级锁车";
            opt2.links = [{ type: "808EJSC", txt: "开启二级锁车"}];
            secControlArr.push(menuLine2(opt2));

            var opt3 = {};
            opt3.name = "解除锁车";
            opt3.links = [{ type: "808JCSC", txt: "解除锁车"}];
            secControlArr.push(menuLine2(opt3));

            var opt4 = {};
            opt4.name = "开启心跳锁";
            opt4.links = [{ type: "808KQXTS", txt: "开启心跳锁"}];
            secControlArr.push(menuLine2(opt4));

            var opt5 = {};
            opt5.name = "关闭心跳锁";
            opt5.links = [{ type: "808GBXTS", txt: "关闭心跳锁"}];
            secControlArr.push(menuLine2(opt5));

            var opt6 = {};
            opt6.name = "二级用户锁车开启";
            opt6.links = [{ type: "808EJSCKQ", txt: "二级用户锁车开启"}];
            secControlArr.push(menuLine2(opt6));

            var opt7 = {};
            opt7.name = "二级用户锁车关闭";
            opt7.links = [{ type: "808EJSCGB", txt: "二级用户锁车关闭"}];
            secControlArr.push(menuLine2(opt7));

            var opt8 = {};
            opt8.name = "二级用户锁车解锁";
            opt8.links = [{ type: "808EJSCJS", txt: "二级用户锁车解锁"}];
            secControlArr.push(menuLine2(opt8));

        } else if (t == 2) {



        }
    } else if (model == 83 || model == 86 || model == 90 || model == 99 || model == 31 || model == 32) {
        if (t == 2) {
            if (model != 31) {
                var opt1 = {};
                opt1.name = mapPage.cutOffPetrol;
                opt1.links = [{ type: "808DYD", txt: mapPage.cutOffPetrol}];
                secControlArr.push(menuLine2(opt1));

                var opt2 = {};
                opt2.name = mapPage.restorePetrol;
                opt2.links = [{ type: "808HFYD", txt: mapPage.restorePetrol}];
                secControlArr.push(menuLine2(opt2));
            }
            var opt3 = {};
            opt3.name = mapPage.deviceFortify;
            opt3.links = [{ type: "808SF", txt: mapPage.deviceFortify}];
            secControlArr.push(menuLine2(opt3));

            var opt4 = {};
            opt4.name = mapPage.deviceDismiss;
            opt4.links = [{ type: "808CF", txt: mapPage.deviceDismiss}];
            secControlArr.push(menuLine2(opt4));

            var opt5 = {};
            opt5.name = yiwen201407.restart;
            opt5.links = [{ type: "808CQ", txt: yiwen201407.restart}];
            secControlArr.push(menuLine2(opt5));


        } else if (t == 1) {

            var opt1 = {};
            opt1.name = yiwen201312.uploadInterval;
            opt1.links = [{ type: "808SCJG", txt: yiwen201312.uploadInterval}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = yiwen201409.adminPhone;
            opt2.links = [{ type: "808ZKHM", txt: yiwen201409.adminPhone}];
            secControlArr.push(menuLine2(opt2));
            if (model != 31) {
                var opt3 = {};
                opt3.name = yiwen201407.setSOS;
                opt3.links = [{ type: "808SOS", txt: yiwen201407.setSOS}];
                secControlArr.push(menuLine2(opt3));
            }
        }
    } else if (model == 95 || model == 124 || model == 197) {
        if (t == 2) {
            var opt1 = {};
            opt1.name = mapPage.deviceFortify;
            opt1.links = [{ type: "808SF", txt: mapPage.deviceFortify}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = mapPage.deviceDismiss;
            opt2.links = [{ type: "808CF", txt: mapPage.deviceDismiss}];
            secControlArr.push(menuLine2(opt2));

            var opt3 = {};
            opt3.name = yiwen201407.restart;
            opt3.links = [{ type: "808CQ", txt: yiwen201407.restart}];
            secControlArr.push(menuLine2(opt3));

            var opt4 = {};
            opt4.name = yiwen202204.zcms;
            opt4.links = [{ type: "808ZCMS", txt: yiwen202204.zcms}];
            secControlArr.push(menuLine2(opt4));

            var opt5 = {};
            opt5.name = yiwen202204.sdms;
            opt5.links = [{ type: "808SDMS", txt: yiwen202204.sdms}];
            secControlArr.push(menuLine2(opt5));

            var opt6 = {};
            opt6.name = yiwen202204.znms;
            opt6.links = [{ type: "808ZNMS", txt: yiwen202204.znms}];
            secControlArr.push(menuLine2(opt6));

        } else if (t == 1) {
            var opt1 = {};
            opt1.name = yiwen201312.uploadInterval;
            opt1.links = [{ type: "808SCJG", txt: yiwen201312.uploadInterval}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = yiwen201409.adminPhone;
            opt2.links = [{ type: "808ZKHM", txt: yiwen201409.adminPhone}];
            secControlArr.push(menuLine2(opt2));

            var opt3 = {};
            opt3.name = yiwen201407.setSOS;
            opt3.links = [{ type: "808SOS", txt: yiwen201407.setSOS}];
            secControlArr.push(menuLine2(opt3));
        }
    } else if (model == 85) {
        if (t == 2) {
            var opt1 = {};
            opt1.name = mapPage.deviceFortify;
            opt1.links = [{ type: "808SF", txt: mapPage.deviceFortify}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = mapPage.deviceDismiss;
            opt2.links = [{ type: "808CF", txt: mapPage.deviceDismiss}];
            secControlArr.push(menuLine2(opt2));
        }
    } else if (model == 510) {
        if (t == 2) {

            var opt3 = {};
            opt3.name = alarmIndexPage.vibration;
            opt3.links = [{ type: "808ZDBJ", txt: alarmIndexPage.vibration}];
            secControlArr.push(menuLine2(opt3));

            var opt2 = {};
            opt2.name = yiwen202404.dmdw;
            opt2.links = [{ type: "808DM", txt: yiwen202404.dmdw}];
            secControlArr.push(menuLine2(opt2));

            var opt4 = {};
            opt4.name = yiwen202404.dgkz;
            opt4.links = [{ type: "808DGXC", txt: yiwen202404.dgkz}];
            secControlArr.push(menuLine2(opt4));
            var opt6 = {};
            opt6.name = yiwen202404.sykz;
            opt6.links = [{ type: "808SYXC", txt: yiwen202404.sykz}];
            secControlArr.push(menuLine2(opt6));
        } else if (t == 1) {

            var opt1 = {};
            opt1.name = yiwen202404.gzms;
            opt1.links = [{ type: "808SCJG2", txt: yiwen202404.gzms}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = yiwen201409.adminPhone;
            opt2.links = [{ type: "808ZKHM", txt: yiwen201409.adminPhone}];
            secControlArr.push(menuLine2(opt2));
        }
    } else if (model == 106 || model == 196) {
        if (t == 2) {
            var opt1 = {};
            opt1.name = mapPage.deviceFortify;
            opt1.links = [{ type: "SF", txt: mapPage.deviceFortify}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = mapPage.deviceDismiss;
            opt2.links = [{ type: "CF", txt: mapPage.deviceDismiss}];
            secControlArr.push(menuLine2(opt2));

            var opt3 = {};
            opt3.name = yiwen201407.restart;
            opt3.links = [{ type: "808CQ", txt: yiwen201407.restart}];
            secControlArr.push(menuLine2(opt3));

            var opt4 = {};
            opt4.name = yiwen202204.zcms;
            opt4.links = [{ type: "NOR", txt: yiwen202204.zcms}];
            secControlArr.push(menuLine2(opt4));

            var opt5 = {};
            opt5.name = yiwen202204.sdms;
            opt5.links = [{ type: "SAV", txt: yiwen202204.sdms}];
            secControlArr.push(menuLine2(opt5));

            var opt6 = {};
            opt6.name = yiwen202204.znms;
            opt6.links = [{ type: "TIM", txt: yiwen202204.znms}];
            secControlArr.push(menuLine2(opt6));

            var opt7 = {};
            opt7.name = yiwen202204.ljdw;
            opt7.links = [{ type: "808DW", txt: yiwen202204.ljdw}];
            secControlArr.push(menuLine2(opt7));

        } else if (t == 1) {
            var opt1 = {};
            opt1.name = yiwen201312.uploadInterval;
            opt1.links = [{ type: "D1", txt: yiwen201312.uploadInterval}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = yiwen201409.adminPhone;
            opt2.links = [{ type: "S7101", txt: yiwen201409.adminPhone}];
            secControlArr.push(menuLine2(opt2));

            var opt3 = {};
            opt3.name = yiwen201407.setSOS;
            opt3.links = [{ type: "S7102", txt: yiwen201407.setSOS}];
            secControlArr.push(menuLine2(opt3));
        }
    } else if (model == 121) {
        if (t == 2) {
            var opt1 = {};
            opt1.name = yiwen202404.cmd1;
            opt1.links = [{ type: "AK9CQ", txt: yiwen202404.cmd1}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = yiwen202106.scgj;
            opt2.links = [{ type: "AK9FORMAT", txt: yiwen202106.scgj}];
            secControlArr.push(menuLine2(opt2));

        } else if (t == 1) {
            var opt1 = {};
            opt1.name = yiwen202405.cmd1;
            opt1.links = [{ type: "AK9D1", txt: yiwen202405.cmd1}];
            secControlArr.push(menuLine2(opt1));

            var opt2 = {};
            opt2.name = yiwen202405.cmd2;
            opt2.links = [{ type: "AK9SPOF", txt: yiwen202405.cmd2}];
            secControlArr.push(menuLine2(opt2));

            var opt3 = {};
            opt3.name = yiwen201409.adminPhone;
            opt3.links = [{ type: "AK9S7101", txt: yiwen201409.adminPhone}];
            secControlArr.push(menuLine2(opt3));

            var opt4 = {};
            opt4.name = yiwen202405.cmd3;
            opt4.links = [{ type: "AK9LED", txt: yiwen202405.cmd3}];
            secControlArr.push(menuLine2(opt4));

            var opt5 = {};
            opt5.name = yiwen202405.cmd4;
            opt5.links = [{ type: "AK9LSPK", txt: yiwen202405.cmd4}];
            secControlArr.push(menuLine2(opt5));

            var opt6 = {};
            opt6.name = yiwen202405.cmd5;
            opt6.links = [{ type: "AK9LPER", txt: yiwen202405.cmd5}];
            secControlArr.push(menuLine2(opt6));

            var opt7 = {};
            opt7.name = yiwen202405.cmd6;
            opt7.links = [{ type: "AK9SWIT", txt: yiwen202405.cmd6}];
            secControlArr.push(menuLine2(opt7));
        }
    } else {
        if (model > 70 && model < 95 || model == 105) {
            if (t == 1) {
                var opt = {};
                opt.name = yiwen201312.uploadInterval;
                opt.links = [{ type: "D1", txt: yiwen201312.uploadInterval}];
                secControlArr.push(menuLine2(opt));
                if (model != 78) {
                    var opt3 = {};
                    opt3.name = yiwen201409.adminPhone;
                    opt3.links = [{ type: "S7101", txt: yiwen201409.adminPhone}];
                    secControlArr.push(menuLine2(opt3));

                    var opt2 = {};
                    opt2.name = yiwen201407.setSOS;
                    opt2.links = [{ type: "S7102", txt: yiwen201407.setSOS}];
                    secControlArr.push(menuLine2(opt2));
                }

            } else if (t == 2) {
                var opt = {};
                opt.name = mapPage.cutOffPetrol;
                opt.links = [{ type: "S201", txt: mapPage.cutOffPetrol}];
                secControlArr.push(menuLine2(opt));

                var opt1 = {};
                opt1.name = mapPage.restorePetrol;
                opt1.links = [{ type: "S200", txt: mapPage.restorePetrol}];
                secControlArr.push(menuLine2(opt1));


                var opt3 = {};
                opt3.name = mapPage.deviceFortify;
                opt3.links = [{ type: "SCF0", txt: mapPage.deviceFortify}];
                secControlArr.push(menuLine2(opt3));

                var opt4 = {};
                opt4.name = mapPage.deviceDismiss;
                opt4.links = [{ type: "SCF1", txt: mapPage.deviceDismiss}];
                secControlArr.push(menuLine2(opt4));
                if (model != 78) {
                    var opt6 = {};
                    opt6.name = yiwen202204.ycqd;
                    opt6.links = [{ type: "S101", txt: yiwen202204.ycqd}];
                    secControlArr.push(menuLine2(opt6));


                    var opt7 = {};
                    opt7.name = yiwen202204.ycxh;
                    opt7.links = [{ type: "S100", txt: yiwen202204.ycxh}];
                    secControlArr.push(menuLine2(opt7));


                    var opt8 = {};
                    opt8.name = yiwen202204.yckm;
                    opt8.links = [{ type: "S111", txt: yiwen202204.yckm}];
                    secControlArr.push(menuLine2(opt8));


                    var opt9 = {};
                    opt9.name = yiwen202204.ycgm;
                    opt9.links = [{ type: "S110", txt: yiwen202204.ycgm}];
                    secControlArr.push(menuLine2(opt9));
                }
            }
        } else if (model == 110) {
            if (t == 2) {
                var opt = {};
                opt.name = yiwen202204.kaisuo;
                opt.links = [{ type: "LOCK0", txt: yiwen202204.kaisuo}];
                secControlArr.push(menuLine2(opt));

                var opt1 = {};
                opt1.name = yiwen202204.guansuo;
                opt1.links = [{ type: "LOCK1", txt: yiwen202204.guansuo}];
                secControlArr.push(menuLine2(opt1));
            }
        } else if (model == 97 || model == 98) {
            if (t == 2) {
                var opt1 = {};
                opt1.name = mapPage.deviceFortify;
                opt1.links = [{ type: "X3SF", txt: mapPage.deviceFortify}];
                secControlArr.push(menuLine2(opt1));

                var opt2 = {};
                opt2.name = mapPage.deviceDismiss;
                opt2.links = [{ type: "X3CF", txt: mapPage.deviceDismiss}];
                secControlArr.push(menuLine2(opt2));

                var opt3 = {};
                opt3.name = mapPage.cutOffPetrol;
                opt3.links = [{ type: "X3DKYD", txt: mapPage.cutOffPetrol}];
                secControlArr.push(menuLine2(opt3));

                var opt4 = {};
                opt4.name = mapPage.restorePetrol;
                opt4.links = [{ type: "X3HFYD", txt: mapPage.restorePetrol}];
                secControlArr.push(menuLine2(opt4));

                var opt4 = {};
                opt4.name = yiwen202204.xckq;
                opt4.links = [{ type: "X3KQJB", txt: yiwen202204.xckq}];
                secControlArr.push(menuLine2(opt4));

                var opt5 = {};
                opt5.name = yiwen202204.xcgb;
                opt5.links = [{ type: "X3GBJB", txt: yiwen202204.xcgb}];
                secControlArr.push(menuLine2(opt5));



            } else if (t == 1) {
                var opt5 = {};
                opt5.name = yiwen202204.zdsf;
                opt5.links = [{ type: "X3ZDSF", txt: yiwen202204.zdsf}];
                secControlArr.push(menuLine2(opt5));

                var opt6 = {};
                opt6.name = yiwen202204.xsdy;
                opt6.links = [{ type: "X3XSDY", txt: yiwen202204.xsdy}];
                secControlArr.push(menuLine2(opt6));

                var opt7 = {};
                opt7.name = yiwen202204.zdlmd;
                opt7.links = [{ type: "X3ZDLMD", txt: yiwen202204.zdlmd}];
                secControlArr.push(menuLine2(opt7));

                var opt8 = {};
                opt8.name = yiwen202204.tclmd;
                opt8.links = [{ type: "X3TCLMD", txt: yiwen202204.tclmd}];
                secControlArr.push(menuLine2(opt8));
                var opt6 = {};
                opt6.name = yiwen202204.jjlxr + "1";
                opt6.links = [{ type: "X3CAB1", txt: yiwen202204.jjlxrhm + "1"}];
                secControlArr.push(menuLine2(opt6));

                var opt7 = {};
                opt7.name = yiwen202204.jjlxr + "2";
                opt7.links = [{ type: "X3CAB2", txt: yiwen202204.jjlxrhm + "2"}];
                secControlArr.push(menuLine2(opt7));

                var opt8 = {};
                opt8.name = yiwen202204.jjlxr + "3";
                opt8.links = [{ type: "X3CAB3", txt: yiwen202204.jjlxrhm + "3"}];
                secControlArr.push(menuLine2(opt8));

            }
        }
    }
    return secControlArr.join('');
}


function menuLine(opt) {
    var arr = [],
    link;
    for (var i = 0, len = opt.links.length; i < len; i++) {
        link = opt.links[i];
        if (link.type) {
            arr.push('<a href="javascript:showForm(\'' + link.id + '\');">' + link.txt + '</a>');
        }
    }
    if (arr.length > 0) {
        return '<li><span>' + opt.name + '</span><menu>' + arr.join('') + '</menu></li>';
    }
    return '';
}

function menuLine2(opt) {
    var arr = [],
    link;
    for (var i = 0, len = opt.links.length; i < len; i++) {
        link = opt.links[i];
        if (link.type) {
            arr.push('<a href="javascript:showCommandType(\'' + link.type + '\',\'' + link.txt + '\');">' + link.txt + '</a>');
            arr.push('|');
        }
    }
    if (arr.length > 0) {
        arr.pop();
        return '<li>' + arr.join('') + '</li>';
    }
    return '';
}


var commandType = "";
var commandTypeX = "";
function showCommandType(type, command) {
    commandType = type;
    $("#spanCommandType").html(command);
    $(".sec-list").hide();
    closeOpenDiv();
    if (commandType == "D1") {
        $("#divSetInterval").show();
        $("#spanSetInterval").html(yiwen201312.msg2);
    } else if (commandType == "808SCJG") {
        $("#divSetInterval").show();
        $("#spanSetInterval").html(yiwen202405.vr + ":5-120" + yiwen202404.seconds);
    } else if (commandType == "808ZNMS" || commandType == "TIM") {
        $("#divSetInterval").show();
        $("#spanSetInterval").html(yiwen202405.vr + ":3-720" + yiwen202404.minutes);
    } else if (commandType == "S7101" || commandType == "808ZKHM") {
        $("#divCenterPhone").show();
    } else if (commandType == "YSZX") {
        $("#divYinshenZaixian").show();
    } else if (commandType == "S7102" || commandType == "808SOS") {
        $("#divSOSPhone").show();
    } else if (commandType == "ANGLE") {
        $("#divQingxie").show();
    } else if (commandType == "RANGE") {
        commandType = "ANGLE";
        commandTypeX = type;
        $("#divRange").show();
    } else if (commandType == "FREQUENCY") {
        commandType = "ANGLE";
        commandTypeX = type;
        $("#divFrequency").show();
    } else if (commandType == "DISTANCE") {
        commandType = "ANGLE";
        commandTypeX = type;
        $("#divDistance").show();
    } else if (commandType == "X3ZDSF") {
        $("#divX3ZDSF").show();
    } else if (commandType == "X3XSDY") {
        $("#divX3XSDY").show();
    } else if (commandType == "X3ZDLMD") {
        $("#divX3ZDLMD").show();
    } else if (commandType == "X3TCLMD") {
        $("#divX3TCLMD").show();
    } else if (commandType == "X3CAB1") {
        $("#divX3CAB1").show();
    } else if (commandType == "X3CAB2") {
        $("#divX3CAB2").show();
    } else if (commandType == "X3CAB3") {
        $("#divX3CAB3").show();
    } else if (commandType == "808ZDBJ") {
        $("#div808ZDBJ").show();
    } else if (commandType == "808DGXC") {
        $("#div808DGXC").show();
    } else if (commandType == "808DGXCSC") {
        $("#div808DGXCSC").show();
    } else if (commandType == "808SYXC") {
        $("#div808SYXC").show();
    } else if (commandType == "808SYXCSC") {
        $("#div808SYXCSC").show();
    } else if (commandType == "808SCJG2") {
        $("#div808SCJG2").show();
    } else if (commandType == "AK9D1") {
        $("#divAK9D1").show();
    } else if (commandType == "AK9SPOF") {
        $("#divAK9SPOF").show();
    } else if (commandType == "AK9S7101") {
        $("#divCenterPhone").show();
    } else if (commandType == "AK9LED") {
        $("#divAK9LED").show();
    } else if (commandType == "AK9LSPK") {
        $("#divAK9LSPK").show();
    } else if (commandType == "AK9LPER") {
        $("#divAK9LPER").show();
    } else if (commandType == "AK9SWIT") {
        $("#divAK9SWIT").show();
    }
}

function closeOpenDiv() {
    $("#divSetInterval").hide();
    $("#divCenterPhone").hide();
    $("#divYinshenZaixian").hide();
    $("#divSOSPhone").hide();
    $("#divQingxie").hide();
    $("#divRange").hide();
    $("#divFrequency").hide();
    $("#divDistance").hide();
    $("#divX3ZDSF").hide();
    $("#divX3XSDY").hide();
    $("#divX3ZDLMD").hide();
    $("#divX3TCLMD").hide();
    $("#divX3CAB1").hide();
    $("#divX3CAB2").hide();
    $("#divX3CAB3").hide();
    $("#div808ZDBJ").hide();
    $("#div808DGXC").hide();
    $("#div808DGXCSC").hide();
    $("#div808SYXC").hide();
    $("#div808SYXCSC").hide();
    $("#div808SCJG2").hide();
    $("#divAK9D1").hide();
    $("#divAK9SPOF").hide();
    $("#divAK9LED").hide();
    $("#divAK9LSPK").hide();
    $("#divAK9LPER").hide();
    $("#divAK9SWIT").hide();
}


//验证密码
function sendCmdInfo() {
    if (commandType == "") {
        alert(yiwen202405.msg3);
        return;
    }
    if (commandType == "D1") {
        var phones = $("#txtSetInterval").val();
        if (phones == "") {
            return;
        }
        if (parseInt(phones) < 10) {
            alert(yiwen201312.msg3);
            return;
        }
    }
    if (commandType == "808SCJG") {
        var phones = $("#txtSetInterval").val();
        if (phones == "") {
            return;
        }
        if (parseInt(phones) < 5 || parseInt(phones) > 120) {
            alert(yiwen202405.vr + ":5-120" + yiwen202404.seconds + "!");
            return;
        }
    }
    if (commandType == "808DGXCSC") {
        var phones = $("#txt808DGXCSC").val();
        if (phones == "") {
            return;
        }
    }
    if (commandType == "808SYXCSC") {
        var phones = $("#txt808SYXCSC").val();
        if (phones == "") {
            return;
        }
    }
    if (commandType == "808ZNMS" || commandType == "TIM") {
        var phones = $("#txtSetInterval").val();
        if (phones == "") {
            return;
        }
        if (parseInt(phones) < 3 || parseInt(phones) > 720) {
            alert(yiwen202405.vr + ":3-720" + yiwen202404.minutes + "!");
            return;
        }
    }
    if (commandType == "ANGLE" && commandTypeX == "") {
        var phones = $("#txtAngle").val();
        if (phones == "") {
            return;
        }
        if (parseInt(phones) < 0 || parseInt(phones) > 100) {
            alert(yiwen202405.vr + ":0-100!");
            return;
        }
    }
    if (commandType == "AK9D1") {
        var phones = $("#selAK9D1").val();
        if (phones == "zdy") {
            phones = $("#txtAK9D1").val();
            if (parseInt(phones) < 10) {
                alert(yiwen201312.msg3);
                return;
            }
        }
    }
    if (commandType == "S7101" || commandType == "AK9S7101") {
        var phones = $("#txtCenterPhone").val();
        if (phones == "") {
            return;
        }
    }
    var loginUserID = userID;

    var pass = $("#txtInputpassword").val();
    if (pass == "") {
        alert(mapPage.passNull);
    } else {
        $("#spanSendMsg").html(mapPage.sendMsg1);
        $.ajax({
            type: "post",
            url: "Ajax/UsersAjax.asmx/ValidPassword",
            contentType: "application/json",
            data: "{UserID:" + loginUserID + ",DeviceID:" + loginDeviceID + ",Pass:'" + pass + "',LoginType:" + loginType + "}",
            dataType: "json",
            error: function (res) {
                //alert(res.responseText);
            },
            success: function (result) {
                var res = parseInt(result.d);
                if (res == 1) {
                    if (commandType == "KKSSOS") {
                        var phones = $("#txtSOSPhone1").val() + "," + $("#txtSOSPhone2").val() + "," + $("#txtSOSPhone3").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "S7101" || commandType == "808ZKHM") {
                        var phones = $("#txtCenterPhone").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "D1" || commandType == "808SCJG" || commandType == "808ZNMS" || commandType == "TIM") {
                        var phones = $("#txtSetInterval").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "YSZX") {
                        var phones = $("#selYinshenZaixian").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "ANGLE") {
                        var phones = $("#txtAngle").val();
                        if (commandTypeX == "RANGE") {
                            phones = $("#txtRange").val();
                        } else if (commandTypeX == "FREQUENCY") {
                            phones = $("#txtFrequency").val();
                        } else if (commandTypeX == "DISTANCE") {
                            phones = $("#txtDistance").val();
                        }
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "RANGE") {//RANGE  FREQUENCY  DISTANCE
                        var phones = $("#txtRange").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "FREQUENCY") {
                        var phones = $("#txtFrequency").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "DISTANCE") {
                        var phones = $("#txtDistance").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "S7102") {
                        var phones = $("#txtSOSPhone1").val() + "-" + $("#txtSOSPhone2").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808SOS") {
                        var phones = $("#txtSOSPhone1").val() + "," + $("#txtSOSPhone2").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3ZDSF") {
                        var phones = $("#selX3ZDSF").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3XSDY") {
                        var phones = $("#selX3XSDY").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3ZDLMD") {
                        var phones = $("#selX3ZDLMD").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3TCLMD") {
                        var phones = $("#selX3TCLMD").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3CAB1") {
                        var phones = $("#txtX3CAB1").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3CAB2") {
                        var phones = $("#txtX3CAB2").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "X3CAB3") {
                        var phones = $("#txtX3CAB3").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808ZDBJ") {
                        var phones = $("#sel808ZDBJ").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808DM") {
                        var phones = "";
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808DGXC") {
                        var phones = $("#sel808DGXC").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808DGXCSC") {
                        var phones = $("#txt808DGXCSC").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808SYXC") {
                        var phones = $("#sel808SYXC").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808SYXCSC") {
                        var phones = $("#txt808SYXCSC").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "808SCJG2") {
                        var phones = $("#sel808SCJG2").val();
                        sendPhoneCommand(sn, deviceID, commandType, model, phones);
                    } else if (commandType == "AK9D1") {
                        var phones = $("#selAK9D1").val();
                        if (phones == "zdy") {
                            phones = $("#txtAK9D1").val();
                        }
                        sendPhoneCommand(sn, deviceID, "D1", model, phones);
                    } else if (commandType == "AK9SPOF") {
                        var v1 = $("#txtKaiTime").val();
                        var v2 = $("#txtGuanTime").val();
                        sendPhoneCommand(sn, deviceID, "SPOF", model, v1 + "," + v2);
                    } else if (commandType == "AK9S7101") {
                        var phones = $("#txtCenterPhone").val();
                        sendPhoneCommand(sn, deviceID, "SPOF", model, phones);
                    } else if (commandType == "AK9LED") {
                        var phones = $("#selAK9LED").val();
                        sendPhoneCommand(sn, deviceID, "LED", model, phones);
                    } else if (commandType == "AK9LSPK") {
                        var phones = $("