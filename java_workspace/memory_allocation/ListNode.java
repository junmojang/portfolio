package LinkedList;
 
public class ListNode {
   public int startAddr;
   public int endAddr;
   public int size;
   public int proc;
   public ListNode next;
   
   public ListNode(int start, int size, int proc){
      this.startAddr = start;
      this.endAddr = start + size-1;
      this.size = size;
      this.proc = proc;
      this.next = null;
   }
   
   public int getProc(){
      return this.proc;
   }
}
