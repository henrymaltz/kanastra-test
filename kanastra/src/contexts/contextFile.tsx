import axios from "axios";
import React, { createContext, ReactNode, useContext, useEffect, useState } from "react";

type File = {
  id: number;
  name: string;
  created_at: string;
  lines: number;
}


type FileContextType = {
  files: File[];
  getFiles: () => Promise<void>;
};

const FileContext = createContext<FileContextType | undefined>(undefined);

interface FileProviderProps {
  children: ReactNode;
}

export const FileProvider: React.FC<FileProviderProps> = ({ children }) => {
  const [files, setFiles] = useState<File[]>([]);

  const getFiles = async () => {
    try {
      const { data } = await axios.get(`http://localhost:8000/api/files`);
      setFiles(data.data);
    } catch (error) {
      console.error("Ocorreu um erro", error);
    }
  };

  useEffect(() => {
    getFiles();

    const interval = setInterval(() => {
      getFiles();
    }, 10000);

    return () => clearInterval(interval);
  }, []);

  return (
    <FileContext.Provider value={{ files, getFiles }}>
      {children}
    </FileContext.Provider>
  );
};

export const useFile = () => {
  const context = useContext(FileContext);
  if (context === undefined) {
    throw new Error("Ocorreu um erro em useFile");
  }
  return context;
};

export const refreshFiles = () => {
  
  const context = useContext(FileContext);
  return context;

}