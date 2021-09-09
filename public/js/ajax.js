function showLieu(){
   let id = document.getElementById("ville").value;
   let text="";
   $.ajax({
      type:"GET",
      url:"ajax/lieu/"+id,
      datatype :'json',
      contentType: "application/x-www-form-urlencoded;charset=utf-8",
      success: function(data){
         console.log(data);
         for(let i=0;i<data.length;i++){
            text += '<option value="'+data[i].id+'">'+data[i].nom+'</option>';
         }
         document.getElementById("lieu").innerHTML=text;

      }
   });
}