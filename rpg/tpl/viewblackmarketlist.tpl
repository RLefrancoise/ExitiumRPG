<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="fr" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta http-equiv="imagetoolbar" content="no" />
		<meta name="resource-type" content="document" />
		<meta name="distribution" content="global" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewblackmarketlist{SD_CSS}.css" />
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript">			
		
			var mode;
			var selected_item = -1;
			
			function set_mode(m) {
				mode = m;
			}
			
			function get_price_of_item(item_place) {
				var p = document.getElementById('price_' + item_place).innerHTML;
				//p = p.split(" ");
				//p = parseInt(p[0]);
				p = parseInt(p, 10);
				
				var q = document.getElementById('quantity_' + item_place).innerHTML;
				q = parseInt(q, 10);
				
				return (p / q);
			}
			
			function update_price_of_item(item_place, price, quantity) {
				document.getElementById('price_' + item_place).innerHTML = (price * quantity);
			}
			
			function inc_quantity(item_place) {
				var p = get_price_of_item(item_place);
				
				var q = document.getElementById('quantity_' + item_place).innerHTML;
				
				q = parseInt(q, 10);
				
				if(q >= 99) return;
				q += 1;
				if(q < 10){
					q = '0' + q.toString();
				}
				else{
					q = q.toString();
				}
				
				document.getElementById('quantity_' + item_place).innerHTML = q;
				update_price_of_item(item_place, p, q);
			}
			
			function dec_quantity(item_place) {
				var p = get_price_of_item(item_place);
				
				var q = document.getElementById('quantity_' + item_place).innerHTML;
				
				q = parseInt(q, 10);
				
				if(q <= 1) return;
				q -= 1;
				if(q < 10){
					q = '0' + q.toString();
				}
				else{
					q = q.toString();
				}
				
				document.getElementById('quantity_' + item_place).innerHTML = q;
				update_price_of_item(item_place, p, q);
			}
			
			function set_selected_item(item_place) {
				//unselect the previous item
				if(selected_item != -1){
					document.getElementById('item_name_' + selected_item).style.color = "rgb(255,128,128)";
					document.getElementById('quantity_selector_' + selected_item).style.visibility = "hidden";
					document.getElementById('items_table').removeAttribute("selected");
				}
			
				selected_item = item_place;
				document.getElementById('item_name_' + item_place).style.color = "rgb(200,255,200)";
				
				if(mode != 'upgrades')
					document.getElementById('quantity_selector_' + item_place).style.visibility = "visible";
					
				document.getElementById('items_table').setAttribute("selected", selected_item);
				window.parent.show_buy_button();
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:set_mode('{MODE}')">
		<table id="items_table" style="font-family:Calibri">
			<!-- BEGIN items_bloc -->
			<tr id="item_{items_bloc.ITEM_PLACE}">
				<td width="290" valign="center" id="item_desc">
					<p><a id="item_name_{items_bloc.ITEM_PLACE}" class="item_name" href="javascript:set_selected_item('{items_bloc.ITEM_PLACE}')">{items_bloc.ITEM_NAME}</a><br><span style="color:white">{items_bloc.ITEM_DESC}</span></p>
				</td>
				<td id="quantity_selector_{items_bloc.ITEM_PLACE}" style="visibility:hidden" width="75" valign="center">
					<table>
						<tr>
							<td><a id="left_arrow" href="javascript:dec_quantity('{items_bloc.ITEM_PLACE}')"><img src="images/rpg/blackmarket/{SD_DIR}buttons/left_arrow.{SD_EXT}"/></a></td>
							<td><span id="quantity_{items_bloc.ITEM_PLACE}" style="color:rgb(0,200,0)">01</span></td>
							<td><a id="right_arrow" href="javascript:inc_quantity('{items_bloc.ITEM_PLACE}')"><img src="images/rpg/blackmarket/{SD_DIR}buttons/right_arrow.{SD_EXT}"/></a></td>
						</tr>
					</table>
				</td>
				<td width="99" align="right" valign="center" id="item_price">
					<p><span id="price_{items_bloc.ITEM_PLACE}" style="color:yellow">{items_bloc.ITEM_PRICE}</span><img src="images/rpg/icons/Ralz2_1.png" style="width:10px;height:12px;margin:auto;margin-left:5px;"/></p>
				</td>
			</tr>
			<!-- END items_bloc -->
		</table>
	</body>
</html>