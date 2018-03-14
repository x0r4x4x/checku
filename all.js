function tableText(tableCell) {
    alert(tableCell.innerHTML);
}

function addCell( newRow, cellText ) {
  var newCell = newRow.insertCell(-1);
  var newText = document.createTextNode( cellText );
  newCell.appendChild(newText);
}

$(document).ready(function(){
   $("button").click(function(){
      $("#maintable tr").remove();
      $.getJSON("summary1.php", function(result){
           var t = document.getElementById("maintable");
           var rI = 0;
           var cI = 1;
           var currentPos = '';
           for( var key in result ) {
              if ( result[key]._id.checkPos != currentPos )
                {
                    if ( currentPos == '' )
                      {
                         r =  t.insertRow();
                         addCell( r, ' ' );
                         addCell( r, "Касса " + result[key]._id.checkPos );
                         addCell( r, ' ' );
                         addCell( r, ' ' );
                         r =  t.insertRow();
                         addCell( r, result[key]._id.month );
                         addCell( r, result[key].total );
                         addCell( r, ' ' );
                         addCell( r, ' ' );
                         currentPos = result[key]._id.checkPos;
                      }
                    else
                      {
                         cI++;
                         rI=0;
                         c = t.rows[rI].cells;
                         c[cI].innerHTML = "Касса " + result[key]._id.checkPos;
                         rI++;
                         c = t.rows[rI].cells; 
                         c[cI].innerHTML = result[key].total;
                         rI++;
                         currentPos = result[key]._id.checkPos;
                      }
                }
              else
                {
                  if ( cI == 1 )
                    {
                      r =  t.insertRow();
                      addCell( r, result[key]._id.month );
                      addCell( r, result[key].total );
                      addCell( r, ' ' );
                      addCell( r, ' ' );
                    }
                  else
                    {
                       c = t.rows[rI].cells;
                       c[cI].innerHTML = result[key].total;
                       rI++;

                   }
               }
            }
           if ( t != null )
             {
              for (var i = 0; i < t.rows.length; i++) 
                {
                   for (var j = 0; j < t.rows[i].cells.length; j++)
                   t.rows[i].cells[j].onclick = function () 
                     {
      $("#maintable tr").remove();
      $.getJSON("summary2.php?mydate=" + this.innerHTML, function(result){
           var t = document.getElementById("maintable");
           var rI = 0;
           var cI = 1;
           var currentPos = '';
           for( var key in result ) {
              if ( result[key]._id.checkPos != currentPos )
                {
                    if ( currentPos == '' )
                      {
                         r =  t.insertRow();
                         addCell( r, ' ' );
                         addCell( r, "Касса " + result[key]._id.checkPos );
                         addCell( r, ' ' );
                         addCell( r, ' ' );
                         r =  t.insertRow();
                         addCell( r, result[key]._id.month );
                         addCell( r, result[key].total );
                         addCell( r, ' ' );
                         addCell( r, ' ' );
                         currentPos = result[key]._id.checkPos;
                      }
                    else
                      {
                         cI++;
                         rI=0;
                         c = t.rows[rI].cells;
                         c[cI].innerHTML = "Касса " + result[key]._id.checkPos;
                         rI++;
                         c = t.rows[rI].cells; 
                         c[cI].innerHTML = result[key].total;
                         rI++;
                         currentPos = result[key]._id.checkPos;
                      }
                }
              else
                {
                  if ( cI == 1 )
                    {
                      r =  t.insertRow();
                      addCell( r, result[key]._id.month );
                      addCell( r, result[key].total );
                      addCell( r, ' ' );
                      addCell( r, ' ' );
                    }
                  else
                    {
                       c = t.rows[rI].cells;
                       c[cI].innerHTML = result[key].total;
                       rI++;

                   }
               }
            }
    });
                     };
                }
             }
        });
    });
});
