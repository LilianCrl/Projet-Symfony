function showLieu(){
   let id = document.getElementById("ville").value;
   let select="<label for=\"lieu\" class=\"cell-4\" >Lieu : </label>" +
               "<select name=\"lieu\"  style=\"width:12em\" onchange=\"showAdresse()\" id=\"lieu\">" +
                  "<option>Selectionner Votre lieu</option>";
   let fSelect="</select>";
   $.ajax({
      type:"GET",
      url:"ajax/lieu/"+id,
      datatype :'json',
      contentType: "application/x-www-form-urlencoded;charset=utf-8",
      success: function(data){
         console.log(data);
         for(let i=0;i<data.length;i++){
            select += '<option value="'+data[i].id+'">'+data[i].nom+'</option>';
         }
         document.getElementById("lieuDiv").innerHTML=select+fSelect;
         document.getElementById("cp").value = data[0].cp;

      }
   });
}

function showAdresse(){
   let id = document.getElementById("lieu").value;
   $.ajax({
      type:"GET",
      url:"ajax/adresse/"+id,
      datatype :'json',
      contentType: "application/x-www-form-urlencoded;charset=utf-8",
      success: function(data){
         console.log(data.rue);
            document.getElementById("rue").value = data.rue;
            document.getElementById("latitude").value = data.latitude;
            document.getElementById("longitude").value = data.longitude;
      }
   });
}