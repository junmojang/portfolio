import java.util.*;
import LinkedList.ListNode;
import LinkedList.LinkedList;

public class mem_alloc {
	
	public static LinkedList AllocList = new LinkedList();
	public static LinkedList FreeList = new LinkedList();
	public static int startAddr = 0;
	public static int MEM_SIZE = 1048576;
	public static int freeproc = 0;
	public static int allocproc;
	public static int flag = 0;
	
	public static void main(String[] args) {
		
		FreeList.insertFirstNode(0, MEM_SIZE, freeproc);
		
        int i = 0;
        int proc_num = Integer.parseInt(args[0]);
        
        for(i=1; i<=proc_num;i++) {
	        if(args[i].startsWith("+")) {
	        	
	        	int size = Integer.parseInt(args[i].substring(3),16);
	        	System.out.print("¸Þ¸ð¸® ÇÒ´ç : proc" + i+ " size : 0x");
	        	System.out.format("%04X%n", size);
	            if(flag == 1)
	        		startAddr = finding_startAddr(size);
	        	alloc_mem(size, startAddr, i);
	        }
	        else if(args[i].startsWith("-")) {
	        	   flag = 1;
		           System.out.println("¸Þ¸ð¸® ÇØÁ¦ : proc" + Integer.parseInt(args[i].substring(1)));
		           free_mem(Integer.parseInt(args[i].substring(1)));
		           check_merge();
	        }
	        else {
	           System.out.println("Arguments Error");
	        }	     
        }
    	System.out.println("Program is done.");
        System.out.println("----------------------------------------------------------------");
        System.out.println("----------------------------------------------------------------");
    	System.out.println("ÇÒ´ç µÈ °ø°£"); System.out.println();
    	AllocList.printList();
        System.out.println("----------------------------------------------------------------");
    	System.out.println("»ç¿ë °¡´ÉÇÑ °ø°£"); System.out.println();
    	FreeList.printList();
	}//main
	
	public static void alloc_mem(int size, int start, int proc) {
		if(flag == 0) {
			if(proc == 1) {
				AllocList.insertFirstNode(start, size, proc);
			}
			else if(proc > 1){
				AllocList.insertLastNode(start, size, proc);
			}
			else {
				System.out.println("Allocation Error");
			}
			startAddr = startAddr + size;
			ListNode rearNode = AllocList.rear;
			AllocList.LastNode(rearNode);
			FreeList.head.startAddr = AllocList.LastNode(rearNode);
			FreeList.head.size = FreeList.head.size - size;
		}
		else {
			ListNode pre = AllocList.findplace(start);
			AllocList.insertMiddleNode(pre, start, size, proc);
			ListNode sth = FreeList.searchNode_startAddr(start);
			sth.startAddr = sth.startAddr + size;
			sth.size = sth.size - size;
			FreeList.EmptyCheck();
		}
		
	}
	public static void free_mem(int proc) {
		
			ListNode found = AllocList.searchNode_proc(proc);
			FreeList.insertfreeNode(found.startAddr, found.size, proc);
			AllocList.deleteMiddleNode(proc);
	}
	public static void check_merge() {
		ListNode prev = FreeList.head;
		ListNode current = prev.next;

		while(current.next != null) {
			if(prev.endAddr+1 == current.startAddr) {
				prev.endAddr = current.endAddr;
				prev.size = prev.size + current.size;
				FreeList.deleteMiddleNode(current.proc);
				current = prev.next;
				continue;
			}
			else{
				prev = prev.next;
				current = current.next;
			}
		}
		
	}
	public static int finding_startAddr(int size) {
		ListNode prev = FreeList.head;
		ListNode current = prev.next;
		
		while(size > prev.size) {
			prev = current;
			current = current.next;
		}
		System.out.print("(Fist Fit)start from : 0x");
		System.out.format("%04X%n",prev.startAddr);
		return prev.startAddr;
	}
}

