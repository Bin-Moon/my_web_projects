
const x = 100;
const y = 90;
var text="";

if( x<y){
    document.getElementById("result").innerText = "y is greater";
}
else{

     document.getElementById("result").innerText = "x is greater";
    }


    for(var i=0;i<11;i++){

        text+=i+"<br>";
    }
     document.getElementById("demo").innerHTML=text;

     function hello(){
        document.getElementById("hi").innerHTML="Hello world"
     }

    var  info = ["Moontaha Islam", " BBH2211001"];
    document.getElementById("output").innerHTML = "Name: " + info[0] + "<br>ID: " + info[1];

    const vehicle=new Object();
    vehicle.type = "car";
    vehicle.name = "corolla";
    vehicle.mileage = "7.6";
    vehicle.color = "blue;"

    document.getElementById("car").innerHTML = 
    vehicle.type+"<br>"
    +vehicle.name +"<br>"
    +vehicle.mileage +"<br>"
    +vehicle.color ;

    class anime{
        constructor(name,year){
            this.name=name;
            this.year=year;
        }
        }
        const anime1=new anime("Lzarus","2025");
        const anime2=new anime("Oshi no ko","2024");

    document.getElementById("anime").innerHTML =
anime1.name + " " +anime1.year+"<br>"
+ anime2.name+" "+anime2.year;