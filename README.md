text-processor-test
===================
**pseudocode**

```sh
Set datafile variable
initialize class and pass datafile path string
if the file exists in the path
	open the datafile in read only form
	set all counters to 0
	create empty array variables for the csv 
	Create array to rearrage the csv array 
	open/create new file to store csv in data path
	open/create new file to store formated data data path
	loop each line of the datafile
		get a line from the datafile
		split the string where 'ITEMS ON RESERVE FOR' exists
		count the number of variables in the splited line array and save the value in variable cntf
		split the string anywhere that there is a space
		count the number of variable in the splited string array and save the value in variable cntb
		if cntf is greater than 1 (this line is an header) 
			set the end counter to the number of lines of the header 
			set other counters to 0
		elseif cntb is equal to 1 (line is an blank/empty) 
			set the end counter to 0 
			set the start counter to 3(the number of lines per data) 
			write it to file
		else process all lines that is not blank
			if end counter is greater than 0	
				reduce the end counter by 1
				split the string by 12 spaces
				filter the array to remove all empty array elements
				Write the data to datafile
				if the end counter is 0 
					set start counter to 3
			if start counter is greater than 0
				reduce start counter by one
				split the string where there is two spaces
				filter the resulting array to remove empty elements
				rearrange the array keys numerically
				if start counter is equal to 2 
					get title and author and write to array 
					write line to file
				if start counter is equal to 1 
					get shelfmark and location code and write to array 
					write line to file
				if start counter is equal to 0 
					get barcode, checkouts, location description and running number and write to array 
					write line to file
	rearrange data to the requested format and save to the rdata array
	write processed data to csv
	Close all opened files
	Return data array
else
	Show error message "the file does not exist"
```
