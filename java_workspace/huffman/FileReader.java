import java.io.IOException;

import java.nio.file.Files;

import java.nio.file.Paths;
import java.util.Collections;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Set;



public class FileReader {

	List<String> lines;

    public int readFileLines(String filePath) throws IOException {

        lines = Files.readAllLines(Paths.get(filePath) );

        StringBuffer sb = new StringBuffer();  // Å×½ºÆ®¿ë º¯¼ö

        for(String line: lines) {

            sb.append(line);

        }

        return sb.length();

    }
    
    public StringBuffer readFile(String filePath) throws IOException {

        lines = Files.readAllLines(Paths.get(filePath) );

        StringBuffer sb = new StringBuffer();  // Å×½ºÆ®¿ë º¯¼ö

        for(String line: lines) {

            sb.append(line);

        }

        return sb;

    }

	

}


class FileReaderEx {

	public static void main(String[] args) throws IOException {
		
		int[] stat = new int[256];
		FileReader f = new FileReader();
		StringBuffer sb = f.readFile("src//huffman.txt");
		
		System.out.println(sb);
		
		for (int i = 0 ; i < sb.length(); i++) {
			char c = sb.charAt(i);
			int c_ind = (int)c;
			stat[c_ind]++;
		}
		
		int max = stat[0];
		int max_index = 0;
		for (int i = 1 ; i < 256 ; i++) {
			if (max < stat[i]) {
				max = stat[i];
				max_index = i;
			}
		}
		
		System.out.println("the most freq: " + (char)max_index + " frequency is " + max);
		
		
		LinkedList<Symbol> syms = new LinkedList<Symbol>();
		
		for(int i = 0; i < 256 ; i++) {
			Symbol s = new Symbol((char)i, stat[i]);
			syms.add(s);
		}

		Collections.sort(syms);
		//Collections.reverse(syms);
		
		System.out.println(
				"The most freq. char from symbol list is '" + syms.get(0).frequency + "'.");
		
		Symbol root = null;
		// build up huffman tree
		while (true) {
			// pick two least freq nodes
			Symbol s1 = syms.removeFirst();
			if (syms.isEmpty()) {
				root = s1;
				break;
			}
			Symbol s2 = syms.removeFirst();

			Symbol newnode = Symbol.buildUp(s1, s2);
			syms.add(newnode);
			Collections.sort(syms);
		}
		System.out.println("total freq: "+root.frequency);
		
		root.labeling("");
		
		/*
		Set<Character> keys = Symbol.HuffmanTable.keySet();
		Iterator<Character> iter = keys.iterator();
		while (iter.hasNext()) {
			Symbol s = iter.next();
			if (s.frequency != 0)
				System.out.println(s);
		}
		*/
		StringBuffer strResult = new StringBuffer();
		for (int i = 0 ; i < sb.length(); i++) {
			char c = sb.charAt(i);
			System.out.println((Symbol.HuffmanTable.get(Character.valueOf(c))));
		}
		System.out.println(strResult);
	}

}





















