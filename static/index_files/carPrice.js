// JScript File
var price = $("#txtMoney").val();
if(price<=0)
{
    price = $("#zdMoney").val();
}

//------------------------common function---------------------------------------
function SetSpanValueByBrowerType(control, value) {
    $("#" + control).html(value);
}
//6701->6,701
function formatCurrency(num) {
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num)) num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    num = Math.floor(num / 100).toString();
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num);
}
//35.3->353000
function formatCurrencyWToK(num) {
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num)) num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 10000 + 0.50000000001).toString();
    return (((sign) ? '' : '-') + num);
}
//4.784->4784
function GetIntValue(num) {
    if(num)
    {
        num = num.toString().replace(/\,/g, '');
        return parseInt(num); 
    }
}

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
function closex(t) {
    $("#" + t).hide(); 
    $("#zhezhao").hide();
}

//排量
var exhaustforfloat;
//车船使用税信息
var vehicleAndVesselTaxInfos = {
    0: {
        Level: 0,
        MinDisplacement: 0,
        MaxDisplacement: 0.0,
        DisplacementDescription: "平行进口 排量参数空",
        Tax: 480
    },
    1: {
        Level: 1,
        MinDisplacement: 0,
        MaxDisplacement: 1.0,
        DisplacementDescription: "1.0L(含)以下",
        Tax: 300
    },
    2: {
        Level: 2,
        MinDisplacement: 1.0,
        MaxDisplacement: 1.6,
        DisplacementDescription: "1.0-1.6L(含)",
        Tax: 420,
        IsDefault: true
    },
    3: {
        Level: 3,
        MinDisplacement: 1.6,
        MaxDisplacement: 2.0,
        DisplacementDescription: "1.6-2.0L(含)",
        Tax: 480
    },
    4: {
        Level: 4,
        MinDisplacement: 2.0,
        MaxDisplacement: 2.5,
        DisplacementDescription: "2.0-2.5L(含)",
        Tax: 900
    },
    5: {
        Level: 5,
        MinDisplacement: 2.5,
        MaxDisplacement: 3.0,
        DisplacementDescription: "2.5-3.0L(含)",
        Tax: 1920
    },
    6: {
        Level: 6,
        MinDisplacement: 3.0,
        MaxDisplacement: 4.0,
        DisplacementDescription: "3.0-4.0L(含)",
        Tax: 3480
    },
    7: {
        Level: 7,
        MinDisplacement: 4.0,
        MaxDisplacement: Number.MAX_VALUE,
        DisplacementDescription: "4.0L以上",
        Tax: 5280
    }
};

//必要花费
/*
function calcEssentialCost() {
    var commonTotal = 0;
    commonTotal += parseInt(calcAcquisitionTax());
    if ($("#txtChePai").val().length == 0) { //光标在文本框中 backspace也需要实时计算
        commonTotal += 0;
    } else {
        commonTotal += parseInt($("#txtChePai").val());
    }
    if ($("#txtVehicleTax").val().length == 0) { //光标在文本框中 backspace也需要实时计算
        commonTotal += 0;
    } else {
        commonTotal += parseInt($("#txtVehicleTax").val());
    }
    commonTotal += GetIntValue(jQuery("#lblCompulsory").html());
    SetSpanValueByBrowerType('essentialCost', formatCurrency(commonTotal));
    return commonTotal;
}
*/

//计算车船使用税
function CalculateVehAndVesselTax() {
    var taxLevel = document.getElementById("vehicleTax").value;
    var vehicleAndVesselTaxValue = vehicleAndVesselTaxInfos[taxLevel].Tax;
    SetSpanValueByBrowerType('txtVehicleTax', Math.ceil(vehicleAndVesselTaxValue));
}

//购置税：购置附加税＝购车款／（1＋17％）× 购置税率（税率：10%）
function calcAcquisitionTax() {
    //alert($("#vehicleTax").val());
    //排量
    var exhaustforfloat = $("#vehicleTax").val();
    var acquisitionTax = (formatCurrencyWToK(price) / 1.17) * 0.1;  
    var result = Math.round(acquisitionTax);
    if (exhaustforfloat >0 ) {
        if (parseFloat(exhaustforfloat) <= 2) {
            result = Math.round((formatCurrencyWToK(price) / 1.17) * 0.1 * 0.75);
            SetSpanValueByBrowerType("lblAcquisitionTax", formatCurrency(result));
            return result;
        }
    }
    SetSpanValueByBrowerType("lblAcquisitionTax", formatCurrency(result));
    return result;
}
//交强险
function calcCompulsory() {
    if ($('#txtMoney').val() == 0) {
        return;
    }
    var compulsoryValue = document.getElementById("selCompulsory").value;
    SetSpanValueByBrowerType('lblCompulsory', formatCurrency(compulsoryValue));
}
//第三者责任险
function calcTPL() {
        var selCompulsoryIndex = '0';// 20W计算
        var selTPLValue = '2' 
        if (selCompulsoryIndex == 0) {
            if (selTPLValue == 0) {
                //$("#lblTPL").html("710");
                return 710;
            }
            if (selTPLValue == 1) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(1026));
                return 1026;
            }
            if (selTPLValue == 2) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(1270));
                return 1270;
            }
            if (selTPLValue == 3) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(1721));
                return 1721;
            }
            if (selTPLValue == 4) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(2242));
                return 2242;
            }
        } else if (selCompulsoryIndex == 1) {
            if (selTPLValue == 0) {
                //jQuery("#lblTPL").html("659");
                return 659;
            }
            if (selTPLValue == 1) {
                //jQuery("#lblTPL").html("928");
                return 928;
            }
            if (selTPLValue == 2) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(1131));
                return 1131;
            }
            if (selTPLValue == 3) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(1507));
                return 1507;
            }
            if (selTPLValue == 4) {
                //SetSpanValueByBrowerType('lblTPL', formatCurrency(1963));
                return 1963;
            }
        }
}
//车辆损失险
function calcCarDamage() {
        var rate = 0.0095;
        var baseCost = 285;
        var seatNum = document.getElementById("selCompulsoryNum").value;
        if (seatNum >= 6 && seatNum < 10) {
            rate = 0.009;
            baseCost = 342;
        } else if (seatNum >= 10 && seatNum < 20) {
            rate = 0.0095;
            baseCost = 342;
        } else if (seatNum >= 20) {
            rate = 0.0095;
            baseCost = 357;
        }
        var result = Math.round(formatCurrencyWToK(price) * rate + baseCost);
       return formatCurrency(result);
}

//发动机特别损失险(车损险*5%)
function calcCarEngineDamage() {
    var cDamage = calcCarDamage();
    var cDamage1 = GetIntValue(cDamage) * 0.05;
    return formatCurrency(Math.round(cDamage1));

}
//全车盗抢险
function calcCarTheft() {
    var selCompulsoryIndex = document.getElementById("selCompulsory").value;
    if (selCompulsoryIndex =='1150') {
        return formatCurrency(Math.round(formatCurrencyWToK(price) * 0.0044 + 140));
        //SetSpanValueByBrowerType('lblCarTheft', formatCurrency(Math.round($('#txtMoney').val() * 0.0044 + 140)));
    } else {
        return formatCurrency(Math.round(formatCurrencyWToK(price) * 0.0049 + 120));
        //SetSpanValueByBrowerType('lblCarTheft', formatCurrency(Math.round($('#txtMoney').val() * 0.0049 + 120)));
    }
}
//玻璃单独破碎险
function calcBreakageOfGlass() {
    return formatCurrency(Math.round(formatCurrencyWToK(price) * 0.0019)); //全部按照国产算
}

function calcBlameless() {
    if ($('#chkTPL').prop("checked") && $('#chkBlameless').prop("checked")) {

        $('#txtBlameless').className = "";
        $('#txtBlameless').val(Math.round($('#txtTPL').val() * 0.2));
    }
    else {
        $('#chkBlameless').attr("checked", false);
        $('#txtBlameless').val("");
        $('#txtBlameless').className = "disablebox";
    }
}

//乘客责任险（//所选金额(20000)*费率*（座位数-1）。如果没有座位数，则*4）
function calcLimitofPassenger() {
    var seatNum = $("#selCompulsory").val();
    var seat = $("#selCompulsoryNum").val();
    var calCount;
    if (seat < 4) {  //小于四座看做没有座位数
        calCount = 4;
    } else {
        calCount = seat - 1;
    }
    if (seatNum =='950') { //6座以下
        var lvalue1 = Math.round(20000 * 0.0027 * calCount);
        return lvalue1;
    } else {
        var lvalue2 = Math.round(20000 * 0.0026 * calCount);
        return lvalue2;
    }
}
//司机责任险
function calcLimitofDriver() {
        var seat = $("#selCompulsoryNum").val();
        if (seat<6) {   //6座以下
            //所选金额(20000)*费率*（座位数-1）。如果没有座位数，则*4
            var lvalue1 = Math.round(20000 * 0.0042);
            return lvalue1;
        } else {
            var lvalue2 = Math.round(20000 * 0.004);
            return lvalue2;
        }
}
//车身划痕险
function calcCarDamageDW() {
    var selCarDamageDWIndex = '1'; //默认5000
    var price1 = formatCurrencyWToK(price);
    if (price1 < 300000) {
        if (selCarDamageDWIndex == 0)
            return '400';
            //jQuery('#lblCarDamageDW').html("400");
        if (selCarDamageDWIndex == 1)
            //jQuery('#lblCarDamageDW').html("570");
            return '570';
        if (selCarDamageDWIndex == 2)
            return '760';
            //jQuery('#lblCarDamageDW').html("760");
        if (selCarDamageDWIndex == 3)
            return '1140';
            //SetSpanValueByBrowerType('lblCarDamageDW', formatCurrency(1140));
    } else if (price1 > 500000) {
        if (selCarDamageDWIndex == 0)
            return '850';
            //jQuery('#lblCarDamageDW').html("850");
        if (selCarDamageDWIndex == 1)
            return '1100';
            //SetSpanValueByBrowerType('lblCarDamageDW', formatCurrency(1100));
        if (selCarDamageDWIndex == 2)
            return '1500';
           // SetSpanValueByBrowerType('lblCarDamageDW', formatCurrency(1500));
        if (selCarDamageDWIndex == 3)
            return '2250';
            //SetSpanValueByBrowerType('lblCarDamageDW', formatCurrency(2250));
    } else {
        if (selCarDamageDWIndex == 0)
            return '585';
            //jQuery('#lblCarDamageDW').html("585");
        if (selCarDamageDWIndex == 1)
            //jQuery('#lblCarDamageDW').html("900");
            return '900';
        if (selCarDamageDWIndex == 2)
            return '1170';
            //SetSpanValueByBrowerType('lblCarDamageDW', formatCurrency(1170));
        if (selCarDamageDWIndex == 3)
            return '1780';
            //SetSpanValueByBrowerType('lblCarDamageDW', formatCurrency(1780));
    }
}

//自燃损失险
function calcSelfignite() {
    return formatCurrency(Math.round(formatCurrencyWToK(price) * 0.0015));
}

//商业保险统计
function calcBusinessTotal() {
    var commonTotal = 0;
    //formatCurrency(Math.round($('#txtMoney').val() * 0.0015))
    //第三者责任险
    var chkTPL = calcTPL();
    commonTotal += parseFloat(GetIntValue(chkTPL));

    //车辆损失险
    var calcCarDamage1 = calcCarDamage();
    commonTotal += GetIntValue(calcCarDamage1);
    //不计免赔特约险(第三者责任险+车辆损失险*20%)
    var calcAbatement = formatCurrency(Math.round((GetIntValue(chkTPL)+GetIntValue(calcCarDamage1))*0.2));
    commonTotal +=GetIntValue(calcAbatement);
    //全车盗抢险
    var calcCarTheft1 = calcCarTheft();
    commonTotal +=GetIntValue(calcCarTheft1);

    //玻璃单独破碎险
    var calcBreakageOfGlass1 = calcBreakageOfGlass();
    commonTotal +=GetIntValue(calcBreakageOfGlass1);

    //自燃损失险
    var calcSelfignite1 = calcSelfignite();
    commonTotal +=GetIntValue(calcSelfignite1);

    //涉水险/发动机特别损失险
    var calcCarEngineDamage1 = calcCarEngineDamage();
    commonTotal +=GetIntValue(calcCarEngineDamage1);

    //车身划痕险
    var calcCarDamageDW1 = calcCarDamageDW();
    commonTotal +=GetIntValue(calcCarDamageDW1);

    //司机座位责任险
    var calcLimitofDriver1 = calcLimitofDriver();
    commonTotal +=GetIntValue(calcLimitofDriver1);

    //乘客座位责任险
    var calcLimitofPassenger1 = calcLimitofPassenger();
    commonTotal +=GetIntValue(calcLimitofPassenger1);

//alert(chkTPL);alert(calcCarDamage1);alert(calcAbatement);alert(calcCarTheft1);alert(calcBreakageOfGlass1);alert(calcSelfignite1);alert(calcCarEngineDamage1);alert(calcCarDamageDW1);alert(calcLimitofDriver1);alert(calcLimitofPassenger1);return false;

    SetSpanValueByBrowerType('lblCommonTotal', formatCurrency(Math.round(commonTotal)));
}

//预计总花费
function calcAll()
{
    var calcBusinessTotal = GetIntValue($("#lblCommonTotal").text());
    var lblCompulsory = GetIntValue($("#lblCompulsory").text());
    var txtChePai = GetIntValue($("#txtChePai").text());
    var txtVehicleTax = GetIntValue($("#txtVehicleTax").text());
    var lblAcquisitionTax = GetIntValue($("#lblAcquisitionTax").text());
    var car_price = GetIntValue(formatCurrencyWToK(price));
    var allCarPirce = calcBusinessTotal+lblCompulsory+txtChePai+txtVehicleTax+lblAcquisitionTax+car_price;
    $("#hid_allCarPrice").val(allCarPirce);
    SetSpanValueByBrowerType('allCarPrice', formatCurrency(Math.round(allCarPirce)));
}
