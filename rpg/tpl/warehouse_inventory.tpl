<!-- BEGIN inventory_item -->
<div style="width:42px;height:42px;border:1px solid rgb(41,64,167);display:inline-block;position:absolute;left:{inventory_item.ITEM_X}px;top:{inventory_item.ITEM_Y}px;margin-left:-21px;margin-top:-21px;"></div>
<img onload="set_image_position(this,{inventory_item.ITEM_X},{inventory_item.ITEM_Y},true)" onclick="{inventory_item.ON_CLICK}" title="{inventory_item.TOOLTIP_TEXT}" onmouseover="{inventory_item.MOUSE_OVER}" onmouseout="{inventory_item.MOUSE_OUT}" style="{inventory_item.STYLE}" src="{inventory_item.ITEM_ICON}"/>
<!-- END inventory_item -->