var ctryCode = "ro";
var langCode = "ro";

var priceDecmPt = parseInt(2);
var weightDecmPt = parseInt(3);
var countryHighlight = "ro";


var optionsLength = new Array(
"Conversia dorita",
"Inci în centimetri",
"Centimetri în inci",
"Inci în milimetri",
"Milimetri în inci",
"Picioare în metri",
"Metri în picioare",
"Yarzi în metri",
"Metri în yarzi",
"Mile în kilometri",
"Kilometri în mile");

var optionsArea=new Array(
"Conversia dorita",
"Inci pãtraþi în centimetri pãtraþi",
"Centimetri pãtraþi în inci pãtraþi",
"Picioare pãtrate în metri pãtraþi",
"Metri pãtraþi în picioare pãtrate",
"Yarzi pãtraþi în metri pãtraþi",
"Metri pãtraþi în yarzi pãtraþi",
"Mile pãtrate în kilometri pãtraþi",
"Kilometri pãtraþi în mile pãtrate ",
"Acri în hectare",
"Hectare în acri");

var optionsVolume = new Array(
"Conversia dorita",
"Inci cubi în centimetri cubi",
"Centimetri cubi în inci cubi",
"Picioare cubice în metri cubi",
"Metri cubi în picioare cubice",
"Yarzi cubi în metri cubi",
"Metri cubi în yarzi cubi",
"Inci cubi în litri",
"Litri în inci cubi",
"Galoni în litri",
"Litri în galoni",
"Galoni americani în litri",
"Litri în galoni americani",
"Uncii fluide în mililitri cubi",
"Mililitri cubi în uncii fluide");

var optionsWeight = new Array(
"Conversia dorita",
"Uncii în grame",
"Grame în uncii",
"Livre în kilograme",
"Klograme în livre",
"Tone lungi în tone metrice",
"Tone metrice în tone lungi",
"Tone scurte în tone metrice",
"Tone metrice în tone scurte");

var optionsForce = new Array(
"Conversia dorita",

"N.m in ozf.in",
"N.m in lbf.ft",
"N.m in kgf.m",             
"ozf.in in N.m",
"ozf.in in lbf.ft",
"ozf.in in kgf.m",         
"lbf.ft in N.m",
"lbf.ft in ozf.in",
"lbf.ft in kgf.m",
"kgf.m in N.m",   
"kgf.m in ozf.in",
"kgf.m in lbf.ft");



var tabLen,tabArea,tabWt,tabVol = true;

var errorStrings=new Array();

var comm_msg002 = "";
var convert_msg001 = "";
var comm_msg003 = "";
var comm_msg039 = "";
var comm_msg041 = "";
var comm_msg042 = "";
var comm_msg004 = "";
var comm_msg039 = "";
var comm_msg041 = "";


errorStrings['converr'] =new Array( comm_msg002,convert_msg001 );
errorStrings['numerr'] =new Array( comm_msg003,comm_msg039 +comm_msg041 + comm_msg042 );
errorStrings['inpterr'] =new Array( comm_msg004,comm_msg039 + comm_msg041 );

var valueLength = new Array(
"1",
"2.54",
"0.39",
"25.40",
"0.04",
"0.31",
"3.28",
"0.91",
"1.09",
"1.61",
"0.62");

var valueArea = new Array(
"1",
"6.451",
"0.15",
"0.09",
"10.76",
"0.84",
"1.20",
"2.59",
"0.39",
"0.40",
"2.47");

var valueVolume = new Array(
"1",
"16.39",
"0.06",
"0.03",
"35.32",
"0.76",
"1.31",
"0.02",
"61.03",
"4.55",
"0.22",
"3.79",
"0.26",
"30.77",
"0.03");

var valueWeight = new Array(
"1",
"28.35",
"0.04",
"0.45",
"2.21",
"1.02",
"0.98",
"0.91",
"1.10");


var valueForce = new Array(
"1",
"141.6",
"0.738",
"0.102",
"0.007",
"0.005",
"0.0007",
"1.365",
"192",
"0.138",
"9.807",
"1389",
"7.233");

selectsArray = new Array(
"lengthSelects",
"areaSelects",
"volumeSelects",
"weightSelects",
"forceSelects");

function makeSelects( )
{
    var index = 0;
    //For length combobox
    for( j=0 ; j< optionsLength.length ;j++ )
    {

        document.lengthForm.lengthSelects.options[j]=new Option( optionsLength[j] );
        document.lengthForm.lengthSelects.options[j].value =valueLength[j];

    }
    document.lengthForm.lengthSelects.selectedIndex = index;


    for( j=0 ; j< optionsArea.length ;j++ )
    {
        document.areaForm.areaSelects.options[j]=new Option( optionsArea[j] );
        document.areaForm.areaSelects.options[j].value =valueArea[j];
    }
   document.areaForm.areaSelects.selectedIndex = index;


    for( j=0 ; j< optionsVolume.length ;j++ )
    {
        document.volumeForm.volumeSelects.options[j]=new Option( optionsVolume[j] );
        document.volumeForm.volumeSelects.options[j].value =valueVolume[j];
    }
  document.volumeForm.volumeSelects.selectedIndex = index;

    for( j=0 ; j< optionsWeight.length ;j++ )
    {
        document.weightForm.weightSelects.options[j]=new Option( optionsWeight[j] );
        document.weightForm.weightSelects.options[j].value =valueWeight[j];
    }
  document.weightForm.weightSelects.selectedIndex = index;

    for( j=0 ; j< optionsForce.length ;j++ )
    {
        document.forceForm.forceSelects.options[j]=new Option( optionsForce[j] );
        document.forceForm.forceSelects.options[j].value =valueForce[j];
    }
  document.forceForm.forceSelects.selectedIndex = index;

}

function convertAlert( stringId )
{
  eName   = errorStrings[stringId][0];
  eText   = errorStrings[stringId][1];
  eWidth  = errorStrings[stringId][2];
  eHeight = errorStrings[stringId][3];

  if( typeof( def_dhlAlert ) != "undefined" )
  {
    dhlAlert( eName, eText, eWidth, eHeight )
  }
  else
  {
    alert( eName+"\n"+eText );
  }
}

function twoDps( item )
{
  return ( parseInt( item * 100 ) / 100 );
}

function convertFunc( formname, num_to_change, mult )
{
  document.forms[formname].num_to_change.value = document.forms[formname].num_to_change.value.replace(',','.');
  //alert(document.forms[formname].num_to_change.value);
  with( document.forms[formname] )
  {
    var num_change = num_to_change.value;
    var mult_by  = mult.options[mult.selectedIndex].value;

    if( mult_by == "" )
    {
      convertAlert( "converr" )
    }
    else
    {
      if( num_change < 0 )
      {
        result.value = "0";
        num_to_change.value = "0";
        convertAlert( "numerr" );
        return ;
      }
      else
      {
        var nresult = twoDps(num_change * mult_by);
        result.value = nresult;
      }
    }

    if( isNaN( nresult ) )
    {
      result.value = "0";
      num_to_change.value = "0";
      convertAlert( "inpterr" )
    }
  }
}
function fnSetTabLen()
{
  if(tabLen)
  {
    document.areaForm.num_to_change.focus();
  }
  else
  {
    document.lengthForm.lengthSelects.focus();
  }
}
function fnSetTabArea()
{
  if(tabArea)
  {
    document.volumeForm.num_to_change.focus();
  }
  else
  {
    document.areaForm.areaSelects.focus();
  }
}
function fnSetTabVol()
{
  if(tabWt)
  {
    document.weightForm.num_to_change.focus();
  }
  else
  {
    document.volumeForm.volumeSelects.focus();
  }
}
