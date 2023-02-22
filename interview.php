
<html>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.js"></script>
	<div id="app">
	<h1>Interview Assignment</h1>	
	<table style="width: 100%;">
		<thead>
		  <tr>
			<th>McDonald Cooking Bot Control Panel</th>
			<th>McDonald Customer Display</th>		
		  </tr>
		</thead>
		<tbody>
		  <tr>
			<td>
			<div><button @click="addRobot">Add Bot</button><button @click="removeRobot">Remove Bot</button>
			</div>
			</td>
			<td>
			<div><button @click="addOrder(false)">Add Order</button><button @click="addOrder(true)" >Add VIP Order</button>
			</div>
			</td>		
		  </tr>
		  <tr><td>
		  <h2>Bots list</h2>
      <ul>
      <li v-for="bot in robots">
        Bot {{ bot.id }} handle {{ bot.order }} 
      </li>
      </ul>
			</td>
			<td>
			<table style="width:100%;"><tr><td style="width:50%;">
			<div>
			<h2>Pending Orders</h2>
    	<ul>
      <li v-for="order in pendingOrders">
        Order-{{ order.orderNumber }} VIP = {{ order.VIP }} 
      </li>
    	</ul>
			<!-- order will be added here -->
			</div>
			</td>
			<td style="width:50%;">
			<div>
			<h2>Completed Orders</h2>
    	<ul>
      <li v-for="order in completedOrders">
        Order-{{ order.orderNumber }} VIP = {{ order.VIP }}
      </li>
    	</ul>
			</div>
			</td>
			</tr>
			</table>
			</td>		
		  </tr>
		</tbody>
		</table>
		</div>
	
	<script>
    var app = new Vue({
      el: '#app',
      data: {
        pendingOrders: [],
        completedOrders: [],
        robots: [],
        orderNumber: 1
      },
      methods: {
      	sortOrdersByVIP() {
		    this.pendingOrders.sort((a, b) => {
		      if (a.VIP && !b.VIP) {
		        return -1;
		      } else if (!a.VIP && b.VIP) {
		        return 1;
		      } else {
		        return 0;
		      }
		    });
		  	},

        addOrder: function(vip) {
          var order = {
            orderNumber: this.orderNumber,
            VIP: vip,   
          };
          this.orderNumber++;
          this.pendingOrders.push(order);
          this.sortOrdersByVIP();
          this.processOrders();
        
        },

        processOrders: function() {
          if (this.robots.length > 0) {
            for (var i = 0; i < this.robots.length; i++) {
              if (this.pendingOrders.some(order => order.VIP === true)) {	
                var order = this.pendingOrders.find(order => order.VIP === true);
                this.processOrder(order);
              } else if (this.pendingOrders.some(order => order.VIP === false)) {
                var order = this.pendingOrders.find(order => order.VIP === false);
                this.processOrder(order);
              } else {
                break;
              }
            }
          }
        },
        processOrder: function(order) {
          var self = this;
          var robot = this.robots.find(robot => robot.order === null);
          if (robot) {
            robot.order = order;
            this.timerId = setTimeout(() => {
              self.completeOrder(robot, order);
            }, 10000);
            
            
          }
        },
        completeOrder: function(robot, order) {
          robot.order = null;
          this.completedOrders.push(order);
          this.pendingOrders.splice(this.pendingOrders.indexOf(order), 1);
          this.processOrders();
        },

        addRobot: function() {
          var robot = {
            id: this.robots.length + 1,
            order: null
          };
          this.robots.push(robot);
          this.processOrders();
        },

        removeRobot: function() {
          var robot = this.robots.find(robot => robot.order !== null);
          if (robot) {
          	clearTimeout(this.timerId);
            robot.order = null;
          }
          this.robots.splice(this.robots.indexOf(robot), 1);
        }        
      }
    });
  </script>

	<style>
		body {
			font-family: Arial, sans-serif;
		}
			
		table, th, td {
			border: 1px solid black;
			
		}
		
		td {
			text-align: center;
		}


	</style>
</html>
