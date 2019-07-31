package LinkedList;
 
public class LinkedList {
   public ListNode head;
   public ListNode rear;
   
   public LinkedList() {
      this.head = null;
      this.rear = null;
   }
   
   public void insertLastNode(int start, int size, int proc){
      ListNode node = new ListNode(start, size, proc);
      if(head == null){
         head = node;
      }else{
         ListNode current = head;
         while(current.next!=null){
            current = current.next;
         }
         current.next = node;
         rear = node;
      }
   }
 
   public void insertMiddleNode(ListNode pre, int start, int size, int proc){
      ListNode node = new ListNode(start, size, proc);
      if(head == null){
         head = node;
         rear = node;
      }else{
         ListNode current = head;
         if(current == pre) {
        	 node.next = current.next;
        	 current.next = node;
         }
         else {
         while(current.next!=pre){
            current = current.next;
         }
         current = current.next;
         node.next = current.next;
         current.next = node;
         }
      }
   }
   
   public void insertfreeNode(int start, int size, int proc){
	         ListNode node = new ListNode(start, size, proc);
	   		 ListNode current = head.next;
	   		 ListNode prev = head;
	   		 
	   		 if(head.startAddr > start) {
	   			 node.next = head;
	   			 head = node;
	   		 }//headº¸´Ù node°¡ ¾Õ¿¡ ÀÖ¾î¾ß ÇÏ´Â °æ¿ì
	   		 else if(head.endAddr+1 == start) {
	        	 node.next = head.next;
	        	 head.next = node;
	         }//head ¹Ù·Î ´ÙÀ½¿¡ ¿Í¾ß ÇÏ´Â °æ¿ì
	         else {
		         while(start+size-1 > current.startAddr){
		        	 prev = current;
		        	 current = current.next;
		         }
		         node.next = current;
		         prev.next = node;
	         }//head º¸´Ù µÚ ´Ù¸¥ ³ëµå¿ÍÀÇ »çÀÌ
	   }
   
   public void insertFirstNode(int start, int size, int proc){
      ListNode node = new ListNode(start, size, proc);
      if(head == null){
         head = node;
         rear = node;
      }else{
         ListNode current = head;
         node.next = current;
         head = node;
      }
   }
   
   public int LastNode(ListNode rearNode) {
	   ListNode node = head;
	   if(head.next == null) {
		   rearNode = node;
		   return rearNode.endAddr;
	   }
	   else {
		   while(node.next != null) {
			   node = node.next;
		   }
		   rearNode = node;
		   return rearNode.endAddr;
	   }
   }
   
   public void deleteLastNode(){
      if(head==null){
         System.out.println("Áö¿ï ³ëµå°¡ Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù.");
      }else{
         ListNode prev = head;
         ListNode current = head.next;
         while(current.next!=null){
            prev = current;
            current = current.next;
         }
         prev.next = null;
      }
   }
 
   public void deleteMiddleNode(int proc){
      if(head==null){
         System.out.println("Áö¿ï ³ëµå°¡ Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù.");
      }else{
         ListNode prev = head;
         ListNode current = head.next;
         if(head.proc == proc) {
        	 head = current;
        	 prev = head;
        	 current = prev.next;
         }
         else {
	         while(current.proc != proc){
	            prev = current;
	            current = current.next;
	         }
	         ListNode NEXT = current.next;
	         prev.next = NEXT;
	         current = NEXT;
         }
      }
   }
 
   public void deleteFirstNode(){
      if(head==null){
         System.out.println("Áö¿ï ³ëµå°¡ Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù.");
      }else{
         head = head.next;
      }
   }
 
   public ListNode searchNode_proc(int proc){
	   //ListNode found = null;
      if(head==null){
         System.out.println("³ëµå°¡ ºñ¾îÀÖ½À´Ï´Ù.");
         return null;
      }else{
         ListNode current = head;
         
         while(current.proc!=proc){
            current = current.next;
            if(current == null) {
            	System.out.println("procÀÌ ÀÏÄ¡ÇÏ´Â ³ëµå°¡ ¾ø½À´Ï´Ù." + proc);
            	return null;
            }
         }
         return current;
      }
   }
   
   public ListNode searchNode_startAddr(int start) {
	   ListNode prev = head;
	   ListNode current = prev.next;
	   
	   if(prev.startAddr == start) {
		   return prev;
	   }
	   else {
		   while(current.startAddr != start) {
			   prev = current;
			   current = current.next;
		   }
		   return current;
	   }
   }
   
   public ListNode findplace(int startAddr) {
	   ListNode prev = head;
	   ListNode current = prev.next;
	   
	   while(true) {
		   if(startAddr >= prev.endAddr && startAddr <= current.startAddr)
			   return prev;
		   else if(current.next != null){
			   prev = current;
			   current = current.next;
		   }
		   else {
			   return rear;
		   }
	   }
   }
   
   public void EmptyCheck() {
	   ListNode prev = head;
	   ListNode current = head.next;
	   
	   if(head.size == 0) {
		   head = current;
		   prev = head;
		   current = prev.next;
	   }
	   else {
		   while(current.next != null) {
			   if(current.size == 0) {
				   ListNode NEXT = current.next;
				   prev.next = NEXT;
				   current = NEXT;
			   }
			   else {
				   prev = current;
				   current = current.next;
			   }
		   }
	   }
   }
   
   public void printList(){
      if(head==null){
         System.out.println("Ãâ·ÂÇÒ ¸®½ºÆ®°¡ Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù.");
      }else{
         ListNode current = head;
  
         while(current.next!=null){
        	 System.out.print("startAddr : 0x");
             System.out.format("%04X      ",current.startAddr);
             System.out.print("endAddr : 0x");
             System.out.format("%04X%n",current.endAddr);
             System.out.print("proc : " + current.proc);
             System.out.print("      size : 0X");
             System.out.format("%04X%n",current.size);
            System.out.println("----------------------------------------------------------------");
            current = current.next;
         }
         System.out.print("startAddr : 0x");
         System.out.format("%04X      ",current.startAddr);
         System.out.print("endAddr : 0x");
         System.out.format("%04X%n",current.endAddr);
         System.out.print("proc : " + current.proc);
         System.out.print("      size : 0X");
         System.out.format("%04X%n",current.size);
         System.out.println("----------------------------------------------------------------");
      }
   }
}
