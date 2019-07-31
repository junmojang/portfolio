import java.util.Scanner;

public class base64_decoding {

   public static void main(String[] args) {
   
               Scanner scan = new Scanner(System.in);
            
               System.out.println("type E or D");
               
               String msg = scan.next();
               
               if(msg.equals("E")) {
                  encode(args);
               }else if(msg.equals("D")) {
                  decode(args);
               }else {
                  System.out.println("Error");
               }
   }

   public static void encode(String[] args){//ASCII -> base64
      System.out.println("E is called " + args[0]);
         String str = args[0];
         
         if(str.length() % 3==1 | str.length()%3==2) {
        	 str = str + "==";
	     }
         
         
         char[] str_char = new char[str.length()]; // ½ºÆ®¸µ -> char
         int[] ASCII = new int[str.length()];      // ASCII.int array
         
         for(int i=0;i<str_char.length;i++){ 
            str_char[i]=(str.charAt(i));         //ASCII½ºÆ®¸µÀ» ÇÑ±ÛÀÚ¾¿ ²÷¾î ¹è¿­¿¡ ÀúÀå
            ASCII[i] = (int)str_char[i]; 
         }
         int[] result = new int[str_char.length/3];
         char[] Base64 = new char[str_char.length*4/3];
         int[] Base64_int = new int[str_char.length*4/3];
         
         
         for(int i=0; i<(str_char.length)/3;i++) {   //3¹®ÀÚ¸¦ ÀÌ¾îÁÖ´Â ÀÛ¾÷
        	 int temp1 = ASCII[3*i]<<16;
             int temp2 = temp1 | (ASCII[3*i+1] <<8);
             result[i] = temp2 | ASCII[3*i+2]; //24bit
         }
         
         for(int j=0; j<(str_char.length)/3; j++) { //result¸¦ 6bit ´ÜÀ§·Î ÀÚ¸£´Â ÀÛ¾÷
        	 	int temp1 = result[j] >> 18;
         		int temp2 = (result[j] >> 12) & 0x3F;
         		int temp3 = (result[j] >> 6) & 0x3F;
         		int temp4 = result[j] & 0x3F;
         		
           
         		Base64_int[4*j] = temp1;
         		Base64_int[4*j+1] = temp2;
         		Base64_int[4*j+2] = temp3;
         		Base64_int[4*j+3] = temp4;
         }
         
         for(int k=0; k<Base64.length; k++) {
        	 if(Base64_int[k] >= 0 && Base64_int[k] <= 25){   			//A~Z ÀÎ°æ¿ì
                 Base64[k] = (char)(Base64_int[k] + 65);
              }else if(Base64_int[k] >= 26 && Base64_int[k] <= 51){   	//a~z ÀÎ°æ¿ì
                 Base64[k] = (char)(Base64_int[k] + 71);
              }else if(Base64_int[k] >= 52 && Base64_int[k] <= 61) { 	//0~9 ÀÎ°æ¿ì
                 Base64[k] = (char)(Base64_int[k] - 4);
              }else if(Base64_int[k] == 62){   							//+ ÀÎ°æ¿ì
                 Base64[k] = (char)(Base64_int[k] - 19);
              }else {   												// / ÀÎ°æ¿ì
                 Base64[k] = (char)(Base64_int[k] - 16);
              }
         }
         String output = new String(Base64);//char ¹è¿­À» stringÀ¸·Î
         System.out.println("Base64 : " + output);
         
      }//3±ÛÀÚ -> 4±ÛÀÚ
   
   public static void decode(String[] args){//base64 -> ASCII
      System.out.println("D is called " + args[0]);
      String str = args[0];
      
      char[] str_char = new char[str.length()];// ½ºÆ®¸µ -> char
      int[] str_int = new int[str.length()];   // char -> int
      int[] Base64 = new int[str.length()];    // ASCII.int -> Base64.int
      
      for(int i=0;i<str_char.length;i++){ 
         str_char[i]=(str.charAt(i));         //½ºÆ®¸µÀ» ÇÑ±ÛÀÚ¾¿ ²÷¾î ¹è¿­¿¡ ÀúÀå
         if(str_char[i] != '=') {            //paddingÀÌ ÀÖÀ» °æ¿ì ¹«½Ã
            str_int[i] = (int)str_char[i];      

         if(str_int[i] >= 65 && str_int[i] <= 90){   //A~Z ÀÎ°æ¿ì
             Base64[i] = str_int[i] - 65;
          }else if(str_int[i] >= 97 && str_int[i] <= 122){   //a~z ÀÎ°æ¿ì
             Base64[i] = str_int[i] - 71;
          }else if(str_int[i] >= 48 && str_int[i] <= 57) { //0~9 ÀÎ°æ¿ì
             Base64[i] = str_int[i] + 4;
          }else if(str_int[i] == 43){   //+ ÀÎ°æ¿ì
             Base64[i] = str_int[i] + 19;
          }else {   // / ÀÎ°æ¿ì
             Base64[i] = str_int[i] + 16;
          }
         }
      }
      
      int[] result = new int[str_int.length*3/4];
      char[] ASCII = new char[str_int.length*3/4];
      
      for(int i=0; i<(str_int.length)/4;i++) {   //4¹®ÀÚ¸¦ ÀÌ¾îÁÖ´Â ÀÛ¾÷
         int temp1 = Base64[4*i]<<18;
          int temp2 = temp1 | (Base64[4*i+1] <<12);
          int temp3 = temp2 | (Base64[4*i+2] <<6);
          result[i] = temp3 | Base64[4*i+3]; 
      }
      
      for(int j=0; j<(str_int.length)/4; j++) { //ÀÌ¾îÁø ¹®ÀÚ¸¦ 8bit ´ÜÀ§·Î ÀÚ¸£´Â ÀÛ¾÷
       int temp1 = result[j] >> 16;
         int   temp2 = (result[j] >> 8) & 0xFF;
        int temp3 = result[j] & 0xFF;
        
        ASCII[3*j] = (char)temp1;
        ASCII[3*j+1] = (char)temp2;
        ASCII[3*j+2] = (char)temp3;
      }
      
      String output = new String(ASCII);   //char ¹è¿­À» stringÀ¸·Î
      System.out.println("ASCII : " + output);
      
      //4±ÛÀÚ -> 3±ÛÀÚ
   }
}
