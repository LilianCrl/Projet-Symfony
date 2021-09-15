function showLieu(){
   let id = document.getElementById("ville").value;
   let select="<label for=\"lieu\" class=\"cell-4\" >Lieu : </label>" +
               "<select name=\"lieu\"  style=\"width:12em\" onchange=\"showAdresse()\" id=\"lieu\">" +
                  "<option>Selectionner Votre lieu</option>";
   let fSelect="</select>";
   $.ajax({
      type:"GET",
      url:"sorties/ajax/lieu/"+id,
      datatype :'json',
      contentType: "application/x-www-form-urlencoded;charset=utf-8",
      success: function(data){
         for(let i=0;i<data.length;i++){
            select += '<option value="'+data[i].id+'">'+data[i].nom+'</option>';
         }
         document.getElementById("lieuDiv").innerHTML=select+fSelect;
         document.getElementById("rue").innerText = "";
         document.getElementById("latitude").innerText = "";
         document.getElementById("longitude").innerText = "";
         document.getElementById("cp").innerText = data[0].cp;

      }
   });
}

function showAdresse(){
   let id = document.getElementById("lieu").value;
   $.ajax({
      type:"GET",
      url:"sorties/ajax/adresse/"+id,
      datatype :'json',
      contentType: "application/x-www-form-urlencoded;charset=utf-8",
      success: function(data){
            document.getElementById("rue").innerText = data.rue;
            document.getElementById("latitude").innerText = data.latitude;
            document.getElementById("longitude").innerText = data.longitude;
      }
   });
}